<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-23
 * Time: 9:44
 */

namespace App\Console\Controllers;

use Inhere\Console\Controller;
use Inhere\Server\Helpers\ServerHelper;
use LightCms\Web\AppServer;

/**
 * ServerController
 */
class ServerController extends Controller
{
    protected static $name = 'server';
    protected static $description = 'manage the swoole application server runtime. [<info>built in</info>]';

    protected function init()
    {
        parent::init();

        $this->showMore = false;
    }

    /**
     * @return AppServer
     */
    protected function createServer()
    {
        /** @var AppServer $server */
        require BASE_PATH . '/src/boot/server.php';

        return $server;
    }

    /**
     * start the application server
     * @options
     *  -d, --daemon  run app server on the background
     */
    public function startCommand()
    {
        ServerHelper::checkRuntimeEnv();
        $daemon = $this->getSameOpt(['d', 'daemon']);

        $this->createServer()->asDaemon($daemon)->start();
    }

    /**
     * restart the application server
     * @options
     *  -d, --daemon  run app server on the background
     */
    public function restartCommand()
    {
        ServerHelper::checkRuntimeEnv();
        $daemon = $this->input->getSameOpt(['d', 'daemon']);

        $this->createServer()->asDaemon($daemon)->restart();
    }

    /**
     * reload the application server
     * @options
     *  --task  only reload task worker when exec reload command
     */
    public function reloadCommand()
    {
        ServerHelper::checkRuntimeEnv();
        $onlyTask = $this->input->getSameOpt(['task']);

        $this->createServer()->reload($onlyTask);
    }

    /**
     * stop the swoole application server
     */
    public function stopCommand()
    {
        ServerHelper::checkRuntimeEnv();
        //$this->write('hello stop');
        $this->createServer()->stop();
    }
}