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
    protected static $description = 'this is a command controller for develop';

    /**
     * run a php built-in server for develop
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
        if (!$server = $this->getOpt('S')) {
            $server = $this->getSameOpt(['H', 'host'], '127.0.0.1');
        }

        if (!strpos($server, ':')) {
            $port = $this->getSameOpt(['p', 'port'], 5577);
            $server .= ':' . $port;
        }

        $this->write("Start the php server on <info>$server</info>");

        exec("php -S {$server} -t web web/index.php");
    }

    /**
     * pack the application to a phar file
     */
    public function packCommand()
    {

    }
}
