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
class HomeController extends Controller
{
    protected static $name = 'home';
    protected static $description = 'this is a default command controller';

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
