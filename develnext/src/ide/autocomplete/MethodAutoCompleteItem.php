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

    static function func($name, $description = '', $insert = null, $icon = null, $style = null)
    {
        $item = new MethodAutoCompleteItem($name, $description, $insert, $icon, $style);
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

    public function getDefaultIcon()
    {
        return 'icons/redLeaf16.png';
    }
}