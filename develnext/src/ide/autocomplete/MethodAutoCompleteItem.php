<?php
namespace ide\autocomplete;

/**
 * Class MethodAutoCompleteItem
 * @package ide\autocomplete
 */
class MethodAutoCompleteItem extends AutoCompleteItem
{
    /**
     * @var bool
     */
    protected $function = false;

    /**
     * @param $name
     * @param string $description
     * @param null $insert
     * @param null $icon
     */
    public function __construct($name, $description = '', $insert = null, $icon = null)
    {
        parent::__construct($name, $description, $insert, $icon);
    }

    static function func($name, $description = '', $insert = null, $icon = null)
    {
        $item = new MethodAutoCompleteItem($name, $description, $insert, $icon);
        $item->function = true;

        return $item;
    }

    /**
     * @return boolean
     */
    public function isFunction()
    {
        return $this->function;
    }
}