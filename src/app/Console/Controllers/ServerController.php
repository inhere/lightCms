<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-23
 * Time: 9:44
 */

namespace App\Console\Controllers;

use Inhere\Console\Controller;
use Micro\Web\AppServer;

/**
 * ServerController
 */
class ServerController extends Controller
{
    protected static $name = 'server';
    protected static $description = 'manage the swoole application server runtime. [<info>built in</info>]';

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
     * run a php built-in server for development(is alias of the command 'dev:serve')
     * @usage
     *  {command} [-S HOST:PORT]
     *  {command} [-H HOST] [-p PORT]
     * @options
     *  -S         The server address. e.g 127.0.0.1:5577
     *  -H,--host  The server host address. e.g 127.0.0.1
     *  -p,--port  The server host address. e.g 5577
     */
    public function devCommand()
    {
        return $this->app->runAction(DevController::getName(), 'serve', true);
    }

    /**
     * start the application server
     * @options
     *  -d, --daemon  run app server on the background
     * @throws \Throwable
     */
    public function startCommand()
    {
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
        $onlyTask = $this->input->getSameOpt(['task']);

        $this->createServer()->reload($onlyTask);
    }

    /**
     * stop the swoole application server
     */
    public function stopCommand()
    {
        $this->createServer()->stop();
    }
}
