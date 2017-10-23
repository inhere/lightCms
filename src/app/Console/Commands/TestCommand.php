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
 * Class Test
 * @package App\Console\Commands
 */
class TestCommand extends Command
{
    /**
     * do execute
     * @param  \Inhere\Console\IO\Input $input
     * @param  \Inhere\Console\IO\Output $output
     * @return int
     */
    protected function execute($input, $output)
    {
        $output->write('hello, this in ' . __METHOD__);

        //AnsiCode::make()->screen(AnsiCode::CLEAR);
    }
}