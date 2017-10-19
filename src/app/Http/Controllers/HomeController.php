<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-18
 * Time: 18:58
 */

namespace App\Http\Controllers;

use LightCms\Web\Controller;

/**
 * class HomeController
 */
class HomeController extends BaseController
{
    public function indexAction()
    {
        $content = "hello, welcome!! this is " . __METHOD__;

        // vd('$_REQUEST :', $_REQUEST, '$_GET :', $_GET);
        $this->renderBody($content);
    }

    public function testAction()
    {
        vd(app()->input->get());
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
