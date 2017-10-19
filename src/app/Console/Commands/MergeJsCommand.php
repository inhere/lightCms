<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-02-27
 * Time: 18:58
 */

namespace app\console\commands;

use inhere\console\utils\AnsiCode;
use micro\console\Command;

/**
 * Class MergeJsCommand
 * @package app\console\commands
 */
class MergeJsCommand extends Command
{
    public function execute()
    {
        $this->output->write('hello, this in ' . __METHOD__);

        $path = $this->output->sOpt('p');
        $clear = $this->output->lBoolOpt('clear');
        $basePath = $this->output->lOpt('base-path');
        $name = $this->output->lOpt('name');
    }
}
