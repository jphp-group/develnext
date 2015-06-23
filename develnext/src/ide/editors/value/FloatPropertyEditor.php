<?php
namespace ide\editors\value;

use php\lib\String;

/**
 * Class IntegerPropertyEditor
 * @package ide\editors\value
 */
class FloatPropertyEditor extends SimpleTextPropertyEditor
{
    public function getNormalizedValue($value)
    {
        return (double) $value;
    }

    public function makeUi()
    {
        return parent::makeUi();
    }
}