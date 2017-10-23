<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-19
 * Time: 16:11
 */

namespace LightCms\Web;

use Exception;
use Inhere\Http\Body;
use Inhere\Route\AbstractRouter;
use LightCms\Exceptions\NotFoundException;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

/**
 * Class RouteDispatcher
 * @package LightCms\Web\Handlers
 */
class RouteDispatcher
{
    /**
     * Allow: false, 'append', 'prepend'
     * @var string
     */
    public $outputBuffering = 'append';

    /**
     * some setting for self
     * @var array
     */
    protected $config = [
        // default action method name
        'defaultAction' => 'index',

        'actionPrefix' => '',

        'actionSuffix' => 'Action',

        // enable dynamic action.
        // e.g
        // if set True;
        //  $router->any('/demo/{act}', app\controllers\Demo::class);
        //  you access '/demo/test' will call 'app\controllers\Demo::test()'
        'dynamicAction' => false,
        // @see ORouter::$globalTokens['act']
        'dynamicActionVar' => 'act',

        // action executor. will auto call controller's executor method to run all action.
        // e.g: 'actionExecutor' => 'run'`
        //  $router->any('/demo/{act}', app\controllers\Demo::class);
        //  you access `/demo/test` will call `app\controllers\Demo::run('test')`
        'actionExecutor' => '', // 'run'
    ];

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface|MessageInterface $response
     * @param array $routeInfo
     * @return ResponseInterface
     * @throws Exception
     * @throws Throwable
     */
    public function dispatch(ServerRequestInterface $request, ResponseInterface $response, array $routeInfo)
    {
        $route = $routeInfo[2];

        $handler = $route['handler'];
        $args['matches'] = $route['matches'] ?? [];
//        $args['options'] = $route['option'];

        if ($this->outputBuffering === false) {
            $newResponse = $this->callRouteHandler($request, $response, $handler, $args);
        } else {
            try {
                ob_start();
                $newResponse = $this->callRouteHandler($request, $response, $handler, $args);
                $output = ob_get_clean();
                // @codeCoverageIgnoreStart
            } catch (Throwable $e) {
                ob_end_clean();
                throw $e;
                // @codeCoverageIgnoreEnd
            } catch (Exception $e) {
                ob_end_clean();
                throw $e;
            }
        }

        if ($newResponse instanceof ResponseInterface) {
            // if route callback returns a ResponseInterface, then use it
            $response = $newResponse;
        } elseif (is_string($newResponse)) {
            // if route callback returns a string, then append it to the response
            if ($response->getBody()->isWritable()) {
                $response->getBody()->write($newResponse);
            }
        }

        if (!empty($output) && $response->getBody()->isWritable()) {
            if ($this->outputBuffering === 'prepend') {
                // prepend output buffer content
                $body = new Body();
                $body->write($output . $response->getBody());
                $response = $response->withBody($body);
            } elseif ($this->outputBuffering === 'append') {
                // append output buffer content
                $response->getBody()->write($output);
            }
        }

        // fire leave event
//        if (isset($options['leave'])) {
//            $this->fireCallback($options['leave'], [$options, $path]);
//        }

        return $response;
    }

    /**
     * execute the matched Route Handler
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $handler The route path handler
     * @param array $args Matched param from path
     * [
     *  'matches' => []
     * ]
     * @return mixed
     * @throws NotFoundException
     * @internal param string $path The route path
     * @internal param string $method The request method
     */
    protected function callRouteHandler(ServerRequestInterface $request, ResponseInterface $response, $handler, array $args = [])
    {
        $vars = $args['matches'];
        $args = array_values($args);

        // un-shift ctx to args
        $ctx = Context::make($request, $response);
        array_unshift($args, $ctx);

        // is a \Closure or a callable object
        if (is_object($handler)) {
            return $handler(...$args);
        }

        //// $handler is string

        // is array ['controller', 'action']
        if (is_array($handler)) {
            $segments = $handler;
        } elseif (is_string($handler)) {
            if (strpos($handler, '@') === false && function_exists($handler)) {
                return $handler(...$args);
            }

            // e.g `controllers\Home@index` Or only `controllers\Home`
            $segments = explode('@', trim($handler));
        } else {
            throw new \InvalidArgumentException('Invalid route handler for the path: ' . $request->getUri()->getPath());
        }

        // Instantiation controller
        /** @var BaseController $controller */
        $controller = new $segments[0]();

        // Already assign action
        if (isset($segments[1])) {
            $action = $segments[1];

            // use dynamic action
        } elseif ($this->config['dynamicAction'] && ($var = $this->config['dynamicActionVar'])) {
            $action = isset($vars[$var]) ? trim($vars[$var], '/') : $this->config['defaultAction'];

            // defined default action
        } elseif (!$action = $this->config['defaultAction']) {
            throw new NotFoundException($request, $response);
        }

        if (method_exists($controller, 'init')) {
            $controller->init($action);
        }

        $action = AbstractRouter::convertNodeStr($action);
        $actionMethod = $action . $this->config['actionSuffix'];

        // if set the 'actionExecutor', the action handle logic by it.
        if ($executor = $this->config['actionExecutor']) {
            return $controller->$executor($actionMethod, $args);
        }

        // action method is not exist
        if (!$action || !method_exists($controller, $actionMethod)) {
            throw new NotFoundException($request, $response);
        }

        // call controller's action method
        return $controller->$actionMethod(...$args);
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = array_merge($this->config, $config);
    }
}
