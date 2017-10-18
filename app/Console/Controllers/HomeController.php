<?php

namespace app\console\controllers;

use Inhere\Console\Utils\Interact;
use LightCms\Console\Controller;

/**
 * default command controller
 */
class Home extends Controller
{
    const DESCRIPTION = 'this is a default command controller';

    /**
     * this is a command's description message
     * the second line text
     * @usage usage message
     * @example example text
     */
    public function indexCommand()
    {
        $this->write("hello, welcome!! this is " . __METHOD__);
    }

    /**
     * a example for use color text output on command
     * @usage ./bin/app home/color
     */
    public function colorCommand()
    {
        if ( !$this->output->supportColor() ) {
            $this->write('Current terminal is not support output color text.');

            return 0;
        }

        $styles = $this->output->getColor()->getStyleNames();
        $this->write('normal text output');

        $this->write('color text output');
        foreach ($styles as $style) {
            $this->output->write("<$style>$style style text</$style>");
        }

        return 0;
    }

    /**
     * output block message text
     * @return int
     */
    public function blockMsgCommand()
    {
        $this->write('block message:');

        foreach (Interact::$defaultBlocks as $type) {
            $this->output->$type("message text");
        }

        return 0;
    }

    /**
     * a example for use arguments on command
     * @usage home/useArgs [arg1=val1 arg2=arg2]
     * @example ./bin/app home/useArgs name=test status=2
     */
    public function useArgsCommand()
    {
        print_r(\Micro::input()->get());
    }

    /**
     * output more format message text
     */
    public function fmtMsgCommand()
    {
        $this->output->title("title");
        $body = 'If screen size could not be detected, or the indentation is greater than the screen size, the text will not be wrapped.' .
        'Word wrap text with indentation to fit the screen size,' .
        'Word wrap text with indentation to fit the screen size,' .
        'Word wrap text with indentation to fit the screen size,' .
        'Word wrap text with indentation to fit the screen size,'
        ;

        $this->output->section("title", $body, [
            'pos' => 'l'
        ]);

        Interact::panel(\Micro::$app->getInternalCommands(), 'Internal Commands', '');
        Interact::aList('Internal Commands', \Micro::$app->getInternalCommands());
    }

    /**
     * output current env info
     */
    public function envCommand()
    {
        $info = [
            'phpVersion' => PHP_VERSION,
            'env'        => \Micro::$app->config('env'),
            'debug'      => \Micro::$app->isDebug(),
        ];

        $this->output->panel($info);
    }
}
