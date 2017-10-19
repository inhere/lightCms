<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-18
 * Time: 18:58
 */

namespace LightCms\Web;

use Inhere\Library\Web\ViewRenderer;
use Inhere\Library\Web\ViewRendererTrait;

/**
 * Class BaseController
 * @package LightCms\Web
 */
abstract class BaseController
{
    use ViewRendererTrait;

    /** @var string id */
    public $id;

    /** @var string action name */
    public $action;

    public function __construct($id = null)
    {
        $this->id = $id ?: get_class($this);
    }

    /**
     * @param string $action
     */
    public function init(string $action)
    {
        $this->action = $action;
    }

    protected function resolveView(string $view)
    {
        return \Sys::alias($view);
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
     * @return ViewRenderer
     */
    public function getRenderer()
    {
        return \Sys::$di->get('renderer');
    }
}
