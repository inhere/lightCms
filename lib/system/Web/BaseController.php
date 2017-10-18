<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-18
 * Time: 18:58
 */

namespace LightCms\Web;

use Inhere\Library\Components\ViewRenderer;

/**
 * Class BaseController
 * @package LightCms\Web
 */
abstract class BaseController
{
    /** @var string id */
    public $id;

    /** @var string action name */
    public $action;

    public function __construct($id = null)
    {
        $this->id = $id ?: get_class($this);
    }

    /*********************************************************************************
     * view method
     *********************************************************************************/

    public function render($view, array $data = [], $layout = null)
    {
        return $this->getRenderer()->render(\Sys::alias($view), $data, $layout);
    }

    public function renderPartial($view, array $data = [])
    {
        return $this->getRenderer()->fetch(\Sys::alias($view), $data);
    }

    public function renderContent($string, array $data = [])
    {
        return $this->getRenderer()->renderContent($string, $data);
    }

    /**
     * @param string $url
     * @param int $status
     * @param Response $response
     * @return mixed
     */
    public function redirect($url, $status = 302, $response = null)
    {
        $response = $response ?: $this->getResponse();

        return $response->redirect($url, $status);
    }

    /**
     * [getRenderer description]
     * @return ViewRenderer
     */
    public function getRenderer()
    {
        return \Sys::$di->get('renderer');
    }
}
