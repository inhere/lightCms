<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-19
 * Time: 18:06
 */

namespace App\Model;

use SimpleAR\LiteRecordModel;

/**
 * Class DefaultDbModel
 * @package App\Model
 */
class DefaultDbModel extends LiteRecordModel
{
    public static function getDb()
    {
        return \Mgr::get('db');
    }
}