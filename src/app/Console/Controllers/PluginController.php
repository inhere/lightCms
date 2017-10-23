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
 * Class PluginController
 */
class PluginController extends Controller
{
    protected static $name = 'plugin';
    protected static $description = 'The plugin manage helper tool. plugin create,pack,publish,install,uninstall';

    /**
     * create a new plugin
     */
    public function createCommand()
    {
        $this->write('hello, welcome!! this is ' . __METHOD__);
    }

    /**
     * package a new plugin
     */
    public function packCommand()
    {
        $this->write('hello, welcome!! this is ' . __METHOD__);
    }

    /**
     * publish a new plugin to repo
     */
    public function publishCommand()
    {
        $this->write('hello, welcome!! this is ' . __METHOD__);
    }

    /**
     * install plugin(s) to current system.
     */
    public function installCommand()
    {
        $this->write('hello, welcome!! this is ' . __METHOD__);
    }

    /**
     * update plugin(s) for the installed.
     */
    public function updateCommand()
    {
        $this->write('hello, welcome!! this is ' . __METHOD__);
    }

    /**
     * Uninstall plugin(s) from current system.
     */
    public function uninstallCommand()
    {
        $this->write('hello, welcome!! this is ' . __METHOD__);
    }
}
