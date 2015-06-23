<?php
namespace ide\editors\value;

use php\lib\String;

/**
 * Class IntegerPropertyEditor
 * @package ide\editors\value
 */
class IntegerPropertyEditor extends SimpleTextPropertyEditor
{
    public function getNormalizedValue($value)
    {
        return (int) $value;
    }

    public function makeUi()
    {
        return parent::makeUi();
    }
}