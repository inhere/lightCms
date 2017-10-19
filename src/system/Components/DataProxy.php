<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-18
 * Time: 18:58
 */

namespace LightCms\Components;

/**
 * Class DataProxy
 * @package LightCms\Components
 */
class DataProxy
{
    /**
     * proxy list
     * @var array
     * [
     *     // 'name' => 'handler',
     *     'getArticleList' => [ArticleDao::class, 'getArticleList'],
     * ]
     */
    protected $proxies = [];

    public function __construct($argument)
    {
        # code...
    }

    public function __call($method, array $args)
    {
        return $this->call($method, array $args);
    }

    public function call($name, array $args)
    {
        if ($this->hasName($name)) {
            $handler = $this->proxies[$name];
            return $handler(...$args);
        }

        throw new \RuntimeException("Called method $name is not exists.");
    }

    public function add($name, $callback)
    {
        # code...
    }

    public function hasName($name)
    {
        return isset($this->proxies[$name]);
    }
}
