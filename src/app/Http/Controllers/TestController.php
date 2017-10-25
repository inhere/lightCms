<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-18
 * Time: 18:58
 */

namespace App\Http\Controllers;

use Inhere\Library\Utils\LiteLogger;
use LightCms\Web\BaseController;

/**
 * class HomeController
 */
class TestController extends BaseController
{
    public function indexAction()
    {
        $content = 'hello, welcome!! this is ' . __METHOD__;

        return $this->renderContent($content);
    }

    public function ctxAction($ctx)
    {
        $content = 'hello, welcome!! this is ' . __METHOD__;

        d($ctx);

        return $this->renderContent($content);
    }

    public function errAction()
    {
        $content = 'hello, welcome!! this is ' . __METHOD__;

        trigger_error('test trigger user error', E_USER_ERROR);
//        trigger_error('test trigger user error', E_USER_WARNING);

        return $this->renderContent($content);
    }

    public function err1Action()
    {
        throw new \TypeError('test Type Error');
    }

    public function err2Action()
    {
        call_not_exists_func();
    }

    public function expAction()
    {
        throw new \RuntimeException('test Exception');
    }

    public function logAction()
    {
        //de(\Mgr::get('config')->all());

//        d(\Mgr::get('logger'));

        \Mgr::get('logger')->info('a message test');
        \Mgr::get('logger')->notice('a notice test');
        \Mgr::get('logger')->flush();

        de(\Mgr::get('logger'));
    }

    public function log1Action()
    {

        $lgr = LiteLogger::make([
            'name' => 'test',
            'splitType' => 'hour',
            'basePath' => BASE_PATH . '/user/tmp',
        ]);

        $lgr->trace('a traced message');
        $lgr->info('a info message');
        var_dump($lgr);

        $lgr->flush();

        echo 'hello';
    }

    public function configAction()
    {
        de(\Mgr::get('config')->all());
    }

    public function json()
    {
        \Mgr::trace('test info');

        Micro::$app->output->json([
            'code' => 0,
            'msg' => 'successful!',
            'data' => [
                'name' => 'value',
            ]
        ]);
    }
}
