<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-19
 * Time: 18:06
 */

namespace Micro\Base;

use SimpleAR\LiteRecordModel;

/**
 * Class DbModel
 * @package Micro\Base
 */
abstract class DbModel extends LiteRecordModel
{
    public static function getDb()
    {
        return \Mgr::get('db');
    }
}