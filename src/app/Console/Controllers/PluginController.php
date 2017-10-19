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
    protected static $description = 'The plugin create,pack,publish,install helper tool';

    /**
     * this is a command's description message
     * the second line text
     * @usage usage message
     * @example example text
     */
    public function indexCommand()
    {
        $this->write('hello, welcome!! this is ' . __METHOD__);
    }
}
