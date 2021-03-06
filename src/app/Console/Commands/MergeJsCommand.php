<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-02-27
 * Time: 18:58
 */

namespace App\Console\Commands;

use Inhere\Console\Command;

/**
 * Class MergeJsCommand
 * @package app\console\commands
 */
class MergeJsCommand extends Command
{
    /**
     * do execute
     * @param  \Inhere\Console\IO\Input $input
     * @param  \Inhere\Console\IO\Output $output
     * @return int
     */
    protected function execute($input, $output)
    {
        $this->output->write('hello, this in ' . __METHOD__);

        $path = $this->input->sOpt('p');
        $clear = $this->input->lBoolOpt('clear');
        $basePath = $this->input->lOpt('base-path');
        $name = $this->input->lOpt('name');
    }
}
