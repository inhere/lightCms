<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-23
 * Time: 9:44
 */

namespace App\Console\Controllers;

use Inhere\Console\Controller;
use LightCms\Web\App;

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
     * @return App
     */
    protected function createApp()
    {
        /* @var  App $application */
        $app = ApplicationContext::getBean('application');
        $app->init($this->input->getScript());

        return $app;
    }

    /**
     * start the swoole application server
     * @options
     *  -d, --daemon  run app server on the background
     */
    public function startCommand()
    {
        //$this->write('hello start');
        require BASE_PATH . '/app/routes.php';
        $daemon = $this->input->getSameOpt(['d', 'daemon']);
        $this->createApp()->asDaemon($daemon)->start();
    }

    /**
     * restart the swoole application server
     * @options
     *  -d, --daemon  run app server on the background
     */
    public function restartCommand()
    {
        require BASE_PATH . '/app/routes.php';
        $daemon = $this->input->getSameOpt(['d', 'daemon']);
        $this->createApp()->asDaemon($daemon)->restart();
    }

    /**
     * reload the swoole application server
     * @options
     *  --task  only reload task worker when exec reload command
     */
    public function reloadCommand()
    {
        //$this->write('hello restart');
        $onlyTask = $this->input->getSameOpt(['task']);

        $this->createApp()->reload($onlyTask);
    }

    /**
     * stop the swoole application server
     */
    public function stopCommand()
    {
        //$this->write('hello stop');
        $this->createApp()->stop();
    }
}