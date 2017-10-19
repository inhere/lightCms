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
 * Class Test
 * @package app\console\commands
 */
class Test extends Command
{
    public function execute()
    {
        $this->out->write('hello, this in ' . __METHOD__);

        //AnsiCode::make()->screen(AnsiCode::CLEAR);
    }
}