<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-09-19
 * Time: 15:06
 */
namespace Micro\Base;

use Inhere\Library\Collections\Configuration;
use Inhere\Library\DI\Container;

/**
 * Class AppTrait
 * @package Micro\Base
 */
trait AppTrait
{
    /**
     * @var Container
     */
    protected $di;

    /**
     * prepare
     */
    protected function prepare()
    {
        /** @var Configuration $config */
        $config = $this->di->get('config');
        
        $timeZone = $config->get('timeZone', 'UTC');
        date_default_timezone_set($timeZone);

        if ($config['debug']) {
            ini_set('display_errors', 'On');
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 'Off');
            error_reporting(E_ERROR);
        }
    }
    
    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->di->get($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getIfExist($id)
    {
        return $this->di->getIfExist($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function has($id)
    {
        return $this->di->has($id);
    }

    /**
     * @return Container
     */
    public function getDi()
    {
        return $this->di;
    }

    /**
     * @param Container $di
     */
    public function setDi(Container $di)
    {
        $this->di = $di;
    }
}
