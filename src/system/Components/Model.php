<?php
/**
 * Created by PhpStorm.
 * User: Inhere
 * Date: 2017/2/19 0019
 * Time: 23:35
 */
namespace Micro\Components;

use inhere\library\collections\SimpleCollection;
use inhere\validate\ValidationTrait;
use Inhere\Library\Type;

/**
 * Class BaseModel
 * @package Micro\Components
 */
class Model extends SimpleCollection
{
    use ValidationTrait;

    /**
     * @var bool
     */
    protected $enableValidate = true;

    /**
     * if true, will only save(insert/update) safe's data -- Through validation's data
     * @var bool
     */
    protected $onlySaveSafeData = true;

    /**
     * Validation class name
     */
    //protected $validateHandler = '\inhere\validate\Validation';

    /**
     * The columns of the model
     * @var array
     */
    private $columns = [];

    /**
     * @param $data
     * @return static
     */
    public static function load($data)
    {
        return new static($data);
    }

    /**
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        parent::__construct($items);

        $this->columns = $this->columns();
    }

    /**
     * define model field list
     * in sub class:
     * ```
     * public function columns()
     * {
     *    return [
     *          // column => type
     *          'id'          => 'int',
     *          'title'       => 'string',
     *          'createTime'  => 'int',
     *    ];
     * }
     * ```
     * @return array
     */
    public function columns()
    {
        return [];
    }

    /**
     * @return array
     */
    public function translates()
    {
        return [
            // 'field' => 'translate',
            // e.g. 'name'=>'名称',
        ];
    }

    /**
     * format column's data type
     * @inheritdoc
     */
    public function set($column, $value)
    {
        // belong to the model.
        if (isset($this->columns[$column])) {
            $type = $this->columns[$column];

            if ($type === Type::INT) {
                $value = (int) $value;
            }
        }
        return parent::set($column, $value);
    }

    /**
     * @return array
     */
    public function getColumnsData()
    {
        $source = $this->onlySaveSafeData ? $this->getSafeData() : $this;
        $data = [];

        foreach ($source as $col => $val) {
            if (isset($this->columns[$col])) {
                $data[$col] = $val;
            }
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }
}
