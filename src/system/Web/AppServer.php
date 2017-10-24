<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-23
 * Time: 9:48
 */

namespace LightCms\Web;

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

    protected function handleHttpRequest(Request $request, Response $response)
    {
        $this->app->handleHttp($request, $response);
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