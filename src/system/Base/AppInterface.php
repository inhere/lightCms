<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/9/3
 * Time: 上午12:57
 */

namespace LightCms\Base;

use Inhere\Library\DI\Container;

/**
 * interface AppInterface
 * @package Sws
 */
interface AppInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function get($id);

    /**
     * @param $id
     * @return mixed
     */
    public function has($id);

    /**
     * @param Container $di
     */
    public function setDi(Container $di);

    /**
     * @return Container
     */
    public function getDi();

    /**
     * @return string
     */
    public function getName();
}
