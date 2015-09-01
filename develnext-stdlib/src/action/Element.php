<?php
namespace action;

use php\gui\UXComboBoxBase;
use php\gui\UXLabel;
use php\gui\UXLabeled;
use php\gui\UXListView;
use php\gui\UXTextInputControl;

/**
 * Class ObjectAction
 * @package action
 */
class Element
{
    static function setText($object, $value)
    {
        if ($object instanceof UXLabeled || $object instanceof UXTextInputControl) {
            $object->text = $value;
        }
    }

    static function appendText($object, $value)
    {
        if ($object instanceof UXLabeled || $object instanceof UXTextInputControl) {
            $object->text .= $value;
        } else if ($object instanceof UXListView || $object instanceof UXComboBoxBase) {
            $object->items->add($value);
        }
    }
}