<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/10/23
 * Time: 下午11:06
 */

namespace App\Console\Controllers;


use Inhere\Console\Controller;
use Inhere\Console\IO\Input;

/**
 * Class AssetController
 * @package App\Console\Controllers
 */
class AssetController extends Controller
{
    protected static $name = 'asset';
    protected static $description = 'There are some assets manage commands.';

    public function installConfigure()
    {
        $this->createDefinition()
            ->addArgument('name', Input::ARG_IS_ARRAY, 'the defined asset name.')
            ->addArgument('name1', Input::ARG_REQUIRED, 'the defined asset name.')
        ;
    }

    /**
     * install asset from input name. there are names defined in config.
     */
    public function installCommand()
    {
        $this->write('test');
    }

    /**
     * merge asset from input name. there are names defined in config.
     *
     * @options
     *  --js The javascript files. multi use ','
     */
    public function mergeCommand()
    {
        $this->write('test');
    }

}
