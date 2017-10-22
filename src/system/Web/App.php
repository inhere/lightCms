<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-18
 * Time: 18:58
 * @ref Slim Framework (https://slimframework.com) `Slim\App`
 */

namespace LightCms\Web;

use Exception;
use Inhere\Http\Body;
use Inhere\Http\Headers;
use Inhere\Http\HttpFactory;
use Inhere\Http\ServerRequest;
use Inhere\Library\Components\ErrorHandler;
use Inhere\Library\DI\Container;
use Inhere\Middleware\MiddlewareAwareTrait;
use Inhere\Route\ORouter;
use Inhere\Route\RouterInterface;

use LightCms\Base\AppTrait;
use LightCms\Exceptions\MethodNotAllowedException;
use LightCms\Exceptions\NotFoundException;
use LightCms\Exceptions\RequestException;
use LightCms\Helpers\HttpHelper;
use LightCms\Web\Handlers\ErrorRenderer;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Throwable;

/**
 * Class App
 * @package LightCms\Web
 */
class App
{
    use AppTrait, MiddlewareAwareTrait;

    /**
     * Current version
     * @var string
     */
    const VERSION = '0.0.1';

    /**
     * @param Container $di
     */
    public function __construct(Container $di = null)
    {
        \Sys::$app = $this;

        $this->di = $di ?: new Container;
        $this->di->registerServiceProvider(new DefaultServicesProvider);

        $this->init();
    }

    protected function init()
    {
        $this->prepare();
//        $errHandler = new ErrorHandler();
    }

    /********************************************************************************
     * request handle methods
     *******************************************************************************/

    /**
     * @param bool $send
     * @return ResponseInterface
     */
    public function run($send = true)
    {
        /** @var ResponseInterface $response */
        $response = $this->di->get('response');
        $response = $this->process($this->di->get('request'), $response);

        if ($send) {
            $this->respond($response);
        }

        return $response;
    }

    /**
     * Process a request
     * This method traverses the application middleware stack and then returns the
     * resultant Response object.
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, ResponseInterface $response)
    {
        // Ensure basePath is set
        $router = $this->di->get('router');

        if (method_exists($router, 'setBasePath') && method_exists($request->getUri(), 'getBasePath')) {
            $router->setBasePath($request->getUri()->getBasePath());
        }

        // Dispatch the Router first if the setting for this is on
        if ($this->di->get('config')['determineRouteBeforeAppMiddleware'] === true) {
            // Dispatch router (note: you won't be able to alter routes after this)
            $request = $this->dispatchRouterAndPrepareRoute($request, $router);
        }

        // Traverse middleware stack
        try {
//            $response = $this->callMiddlewareStack($request, $response);
            $response = $this->handle($request, $response);
        } catch (Exception $e) {
            $response = $this->handleException($e, $request, $response);
        } catch (Throwable $e) {
            $response = $this->handlePhpError($e, $request, $response);
        }

        $response = $this->finalize($response);

        return $response;
    }

    /**
     * Send the response the client
     * @param ResponseInterface $response
     */
    public function respond(ResponseInterface $response)
    {
        HttpHelper::respond($response, $this->di->get('config')->get('response'));
    }

    /**
     * end request
     * @param  ResponseInterface|string $response
     */
    public function end($response = null)
    {
        if (is_string($response)) {
            $response = $this->di->get('response')->write($response);
        }

        if ($response instanceof ResponseInterface) {
            $this->respond($response);
        }

        exit(0);
    }

    /**
     * Invoke application
     *
     * This method implements the middleware interface. It receives
     * Request and Response objects, and it returns a Response object
     * after compiling the routes registered in the Router and dispatching
     * the Request object to the appropriate Route callback routine.
     *
     * @param  ServerRequestInterface $request  The most recent Request object
     * @param  ResponseInterface      $response The most recent Response object
     *
     * @return ResponseInterface
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function handle(ServerRequestInterface $request, ResponseInterface $response)
    {
        // Get the route info
        $routeInfo = $request->getAttribute('routeInfo');

        /** @var RouterInterface $router */
        $router = $this->di->get('router');

        // If router hasn't been dispatched or the URI changed then dispatch
        if (null === $routeInfo || ($routeInfo['request'] !== [$request->getMethod(), (string) $request->getUri()])) {
            $request = $this->dispatchRouterAndPrepareRoute($request, $router);
            $routeInfo = $request->getAttribute('routeInfo');
        }

        unset($routeInfo['request']);

        if ($routeInfo[0] === RouterInterface::FOUND) {
            /** @see RouteDispatcher::dispatch() */
            return $this->di->get('routeDispatcher')->dispatch($request, $response, $routeInfo);
        }

        /** @var callable $handler */

        if ($routeInfo[0] === RouterInterface::METHOD_NOT_ALLOWED) {
            if (!$handler = $this->di->getIfExist('notAllowedHandler')) {
                throw new MethodNotAllowedException($request, $response, $routeInfo[1]);
            }

            return $handler($request, $response, $routeInfo[2]);
        }

        if (!$handler = $this->di->getIfExist('notFoundHandler')) {
            throw new NotFoundException($request, $response);
        }

        return $handler($request, $response);
    }

    /**
     * Perform a sub-request from within an application route
     * This method allows you to prepare and initiate a sub-request, run within
     * the context of the current request. This WILL NOT issue a remote HTTP
     * request. Instead, it will route the provided URL, method, headers,
     * cookies, body, and server variables against the set of registered
     * application routes. The result response object is returned.
     * @param  string $method The request method (e.g., GET, POST, PUT, etc.)
     * @param  string $path The request URI path
     * @param  string $query The request URI query string
     * @param  array $headersData The request headers (key-value array)
     * @param  array $cookies The request cookies (key-value array)
     * @param  string $bodyContent The request body
     * @param  ResponseInterface $response The response object (optional)
     * @return ResponseInterface
     */
    public function subRequest(
        $method, $path, $query = '', array $headersData = [], array $cookies = [],
        $bodyContent = '', ResponseInterface $response = null
    )
    {
        $env = $this->di->get('environment');
        $uri = HttpFactory::createUriFromArray($env)->withPath($path)->withQuery($query);
        $headers = new Headers($headersData);
        $serverParams = $env->all();
        $body = new Body('rb+');
        $body->write($bodyContent);
        $body->rewind();
        $request = new ServerRequest($method, $uri, $headers, $cookies, $serverParams, $body);

        if (!$response) {
            $response = $this->di->get('response');
        }

        try {
            return $this->handle($request, $response);
        } catch (\Throwable $e) {
            return $this->handlePhpError($e, $request, $response);
        }
    }

    /**
     * Dispatch the router to find the route. Prepare the route for use.
     * @param ServerRequestInterface|ServerRequest $request
     * @param RouterInterface|ORouter $router
     * @return ServerRequestInterface
     */
    protected function dispatchRouterAndPrepareRoute(ServerRequestInterface $request, RouterInterface $router)
    {
        $uriPath = '/' . ltrim($request->getUri()->getPath(), '/');

        // if 'filterFavicon' setting is TRUE
        if ($uriPath === HttpHelper::FAV_ICON && $this->di->get('config')['filterFavicon']) {
            $this->end('+ICON');
        }

        $routeInfo = $router->match($uriPath, $request->getMethod());

        if ($routeInfo[0] === RouterInterface::FOUND) {
            if (isset($routeInfo[2]['matches'])) {
                $request->setAttributes($routeInfo[2]['matches']);
            }

            $request->setAttribute('routeOption', $routeInfo[2]['option']);
        }

        $routeInfo['request'] = [$request->getMethod(), (string)$request->getUri()];

        return $request->withAttribute('routeInfo', $routeInfo);
    }

    /**
     * Finalize response
     * @param ResponseInterface|MessageInterface $response
     * @return ResponseInterface
     */
    protected function finalize(ResponseInterface $response)
    {
        // stop PHP sending a Content-Type automatically
        ini_set('default_mimetype', '');

        if (HttpHelper::isEmptyResponse($response)) {
            return $response->withoutHeader('Content-Type')->withoutHeader('Content-Length');
        }

        // Add Content-Length header if `addContentLengthHeader` setting is set
        if ($this->get('config')->get('response.addContentLengthHeader')) {
            if (ob_get_length() > 0) {
                throw new \RuntimeException('Unexpected data in output buffer. Maybe you have characters before an opening "<?php" tag?');
            }

            $size = $response->getBody()->getSize();

            if ($size !== null && !$response->hasHeader('Content-Length')) {
                $response = $response->withHeader('Content-Length', (string)$size);
            }
        }

        return $response;
    }

    /**
     * Call relevant handler from the Container if needed. If it doesn't exist,
     * then just re-throw.
     * @param  Exception $e
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface $response
     * @return ResponseInterface
     * throws Exception if a handler is needed and not found
     */
    protected function handleException(Exception $e, ServerRequestInterface $request, ResponseInterface $response)
    {
        if ($e instanceof MethodNotAllowedException) {
            $handler = 'notAllowedHandler';
            $params = [$e->getRequest(), $e->getResponse(), $e->getAllowedMethods()];
        } elseif ($e instanceof NotFoundException) {
            $handler = 'notFoundHandler';
            $params = [$e->getRequest(), $e->getResponse(), $e];
        } elseif ($e instanceof RequestException) {
            // This is a Stop exception and contains the response
            return $e->getResponse();
        } else {
            // Other exception, use $request and $response params
            $handler = 'errorHandler';
            $params = [$request, $response, $e];
        }

        /** @var ErrorRenderer $callable */
        if ($callable = $this->di->getIfExist($handler)) {
            // Call the registered handler
            return $callable(...$params);
        }

        // No handlers found, so just throw the exception
        // throw $e;
        $body = new Body();
        $body->write('Server Exception: ' . $e->getMessage());
        $body->rewind();

        return $response->withBody($body);
    }

    /**
     * Call relevant handler from the Container if needed. If it doesn't exist,
     * then just re-throw.
     * @param  Throwable $e
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface $response
     * @return ResponseInterface
     * throws Throwable
     */
    protected function handlePhpError(Throwable $e, ServerRequestInterface $request, ResponseInterface $response)
    {
        $handler = 'errorHandler';

        /** @var ErrorRenderer $callable */
        if ($callable = $this->di->getIfExist($handler)) {
            // Call the registered handler
            return $callable($request, $response, $e);
        }

        // No handlers found, so just throw the exception
        // throw $e;

        $body = new Body();
        $body->write('Server Error: ' . $e->getMessage());
        $body->rewind();

        return $response->withBody($body);
    }
}
