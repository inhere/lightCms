<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-18
 * Time: 18:58
 */

namespace App\Console\Controllers;

use Inhere\Console\Controller;

/**
 * default command controller
 */
class DevController extends Controller
{
    protected static $name = 'dev';
    protected static $description = 'Some useful development tool commands';

    /**
     * run a php built-in server for development(is alias of the command 'server:dev')
     * @usage
     *  {command} [-S HOST:PORT]
     *  {command} [-H HOST] [-p PORT]
     * @options
     *  -S         The server address. e.g 127.0.0.1:5577
     *  -H,--host  The server host address. e.g 127.0.0.1
     *  -p,--port  The server host address. e.g 5577
     */
    public function serveCommand()
    {
        return $this->app->runAction(ServerController::getName(), 'dev', true);
    }

    /**
     * pack the application to a phar file
     */
    public function packCommand()
    {

    }
}
