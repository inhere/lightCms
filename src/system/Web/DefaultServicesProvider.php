<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-18
 * Time: 18:58
 */
namespace LightCms\Web;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use LightCms\Web\Handlers\PhpError;
use LightCms\Web\Handlers\Error;
use LightCms\Web\Handlers\NotFound;
use LightCms\Web\Handlers\NotAllowed;
use LightCms\Web\Handlers\Strategies\RequestResponse;

use Inhere\Http\Environment;
use Inhere\Http\Headers;
use Inhere\Http\Request;
use Inhere\Http\Response;
use Inhere\Route\ORouter;
use Inhere\Library\DI\Container;
use Inhere\Library\DI\ServiceProviderInterface;

use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\Http\EnvironmentInterface;
use Slim\Interfaces\InvocationStrategyInterface;

/**
 * Slim's default Service Provider.
 */
class DefaultServicesProvider implements ServiceProviderInterface
{
    /**
     * Register Slim's default services.
     *
     * @param Container $di A DI container implementing ArrayAccess and container-interop.
     */
    public function register(Container $di)
    {
        if (!isset($di['environment'])) {
            /**
             * This service MUST return a shared instance
             * of \Slim\Interfaces\Http\EnvironmentInterface.
             * @return EnvironmentInterface
             */
            $di['environment'] = function () {
                return new Environment($_SERVER);
            };
        }

        if (!isset($di['request'])) {
            /**
             * PSR-7 Request object
             * @param Container $di
             * @return ServerRequestInterface
             */
            $di['request'] = function ($di) {
                return Request::createFromEnvironment($di->get('environment'));
            };
        }

        if (!isset($di['response'])) {
            /**
             * PSR-7 Response object
             * @param Container $di
             * @return ResponseInterface
             */
            $di['response'] = function ($di) {
                $headers = new Headers(['Content-Type' => 'text/html; charset=UTF-8']);
                $response = new Response(200, $headers);

                return $response->withProtocolVersion($di->get('settings')['httpVersion']);
            };
        }

        if (!isset($di['router'])) {
            /**
             * This service MUST return a SHARED instance
             * of \Slim\Interfaces\RouterInterface.
             * @param Container $di
             * @return RouterInterface
             */
            $di['router'] = function ($di) {
                $routerCacheFile = false;
                if (isset($di->get('settings')['routerCacheFile'])) {
                    $routerCacheFile = $di->get('settings')['routerCacheFile'];
                }


                $router = (new Router)->setCacheFile($routerCacheFile);
                if (method_exists($router, 'setContainer')) {
                    $router->setContainer($di);
                }

                return $router;
            };
        }

        if (!isset($di['foundHandler'])) {
            /**
             * This service MUST return a SHARED instance
             * of \Slim\Interfaces\InvocationStrategyInterface.
             * @return InvocationStrategyInterface
             */
            $di['foundHandler'] = function () {
                return new RequestResponse;
            };
        }

        if (!isset($di['phpErrorHandler'])) {
            /**
             * This service MUST return a callable
             * that accepts three arguments:
             * 1. Instance of \Psr\Http\Message\ServerRequestInterface
             * 2. Instance of \Psr\Http\Message\ResponseInterface
             * 3. Instance of \Error
             * The callable MUST return an instance of
             * \Psr\Http\Message\ResponseInterface.
             * @param Container $di
             * @return callable
             */
            $di['phpErrorHandler'] = function ($di) {
                return new PhpError($di->get('settings')['displayErrorDetails']);
            };
        }

        if (!isset($di['errorHandler'])) {
            /**
             * This service MUST return a callable
             * that accepts three arguments:
             * 1. Instance of \Psr\Http\Message\ServerRequestInterface
             * 2. Instance of \Psr\Http\Message\ResponseInterface
             * 3. Instance of \Exception
             * The callable MUST return an instance of
             * \Psr\Http\Message\ResponseInterface.
             * @param Container $di
             * @return callable
             */
            $di['errorHandler'] = function ($di) {
                return new Error($di->get('settings')['displayErrorDetails']);
            };
        }

        if (!isset($di['notFoundHandler'])) {
            /**
             * This service MUST return a callable
             * that accepts two arguments:
             *  1. Instance of \Psr\Http\Message\ServerRequestInterface
             *  2. Instance of \Psr\Http\Message\ResponseInterface
             * The callable MUST return an instance of
             * \Psr\Http\Message\ResponseInterface.
             * @return callable
             */
            $di['notFoundHandler'] = function () {
                return new NotFound;
            };
        }

        if (!isset($di['notAllowedHandler'])) {
            /**
             * This service MUST return a callable
             * that accepts three arguments:
             * 1. Instance of \Psr\Http\Message\ServerRequestInterface
             * 2. Instance of \Psr\Http\Message\ResponseInterface
             * 3. Array of allowed HTTP methods
             * The callable MUST return an instance of
             * \Psr\Http\Message\ResponseInterface.
             * @return callable
             */
            $di['notAllowedHandler'] = function () {
                return new NotAllowed;
            };
        }

        if (!isset($di['callableResolver'])) {
            /**
             * Instance of \Slim\Interfaces\CallableResolverInterface
             * @param Container $di
             * @return CallableResolverInterface
             */
            $di['callableResolver'] = function ($di) {
                return new CallableResolver($di);
            };
        }
    }
}
