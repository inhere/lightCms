<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-19
 * Time: 18:06
 */

namespace App\Model;

/**
 * Class CategoryModel
 * @package App\Model
 */
class CategoryModel extends DefaultDbModel
{
    protected static $pkName = 'cate_ID';

    protected $onlySaveSafeData = false;

    public function columns()
    {
        return [
            'cate_ID' => 'int',
            'cate_Name' => 'string',
            'cate_Alias' => 'string',
            'cate_Intro' => 'string',
            'cate_Meta' => 'string',
        ];
    }
}