<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-23
 * Time: 9:48
 */

namespace LightCms\Web;

use Inhere\Server\Helpers\Psr7Http;
use Inhere\Server\Servers\HttpServer;
use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 * Class AppServer
 * @package LightCms\Web
 */
class AppServer extends HttpServer
{
    /** @var  App */
    private $app;

    /**
     * @param Request $request
     * @param Response $response
     */
    protected function handleHttpRequest(Request $request, Response $response)
    {
        $psr7Req = Psr7Http::createRequest($request);
        $psr7Res = Psr7Http::createResponse([
            'Content-Type' => 'text/html; charset=' . \Sys::get('config')->get('charset', 'UTF-8')
        ]);

        // handle request
        $psr7Res = $this->app->handleHttp($psr7Req, $psr7Res);

        Psr7Http::respond($psr7Res, $response);
    }

    /**
     * @return App
     */
    public function getApp(): App
    {
        return $this->app;
    }

    /**
     * @param App $app
     */
    public function setApp(App $app)
    {
        $this->app = $app;
    }
}
