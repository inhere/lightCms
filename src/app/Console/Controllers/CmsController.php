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
class CmsController extends Controller
{
    protected static $name = 'cms';
    protected static $description = 'this CMS manage commands. install, uninstall, update';

    /**
     * install and init the CMS to current system.
     */
    public function installCommand()
    {
        $this->write('hello, welcome!! this is ' . __METHOD__);
    }

    /**
     * update the CMS from git repo.
     */
    public function updateCommand()
    {
        $this->write('hello, welcome!! this is ' . __METHOD__);
    }

    /**
     * update the CMS from git repo. is alias of the 'update'
     */
    public function upgradeCommand()
    {
        $this->write('hello, welcome!! this is ' . __METHOD__);
    }

    /**
     * Uninstall the CMS from current system.
     */
    public function uninstallCommand()
    {
        $this->write('hello, welcome!! this is ' . __METHOD__);
    }
}
