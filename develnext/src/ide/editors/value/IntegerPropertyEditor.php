<?php
namespace ide\editors\value;

use php\gui\layout\UXHBox;
use php\gui\UXSpinner;
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

    public function getCode()
    {
        return 'integer';
    }
}