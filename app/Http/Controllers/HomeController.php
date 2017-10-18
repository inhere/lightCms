<?php

namespace app\http\controllers;

use Micro;
use micro\web\Controller;

/**
 *
 */
class Home extends Controller
{
    public function indexAction()
    {
        $content = "hello, welcome!! this is " . __METHOD__;

        // vd('$_REQUEST :', $_REQUEST, '$_GET :', $_GET);
        $this->renderBody($content);
    }

    public function testAction()
    {
        vd(Micro::$app->input->get());
    }

    public function env()
    {
        Micro::$app->output->formatJson([
            'phpVersion' => PHP_VERSION,
            'env' => Micro::$app->config('env'),
            'debug' => Micro::$app->isDebug(),
        ]);
    }

    public function json()
    {
        Micro::logger()->trace('test info');

        Micro::$app->output->json([
            'code' => 0,
            'msg' => 'successful!',
            'data' => [
                'name' => 'value',
            ]
        ]);
    }

    public function notFound()
    {
        echo "ohh!! page not found.";
    }
}
