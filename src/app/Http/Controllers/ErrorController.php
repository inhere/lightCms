<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-18
 * Time: 18:58
 */

namespace App\Http\Controllers;

use LightCms\Web\BaseController;

/**
 * class ErrorController
 */
class ErrorController extends BaseController
{
    public function indexAction()
    {
        return $this->errorAction();
    }

    public function errorAction()
    {
        return $this->render('@lib/resources/views/500.tpl');
    }

    public function notFoundAction()
    {
        return $this->render('@lib/resources/views/404.tpl');
    }

    public function notAllowedAction()
    {
        return $this->render('@lib/resources/views/405.tpl');
    }
}
