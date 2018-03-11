<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\gui\framework\DataUtils;
use php\gui\UXComboBox;
use php\gui\UXComboBoxBase;
use php\gui\UXDatePicker;
use php\gui\UXTextInputControl;
use php\xml\DomDocument;
use php\xml\DomElement;

class DatePickerFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'DatePicker';
    }

    public function getElementClass()
    {
        return UXDatePicker::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXDatePicker $node */
        $element->setAttribute('showWeekNumbers', $node->showWeekNumbers ? 'true' : 'false');

        $element->removeAttribute('value');

        DataUtils::get($node)->set('format', $node->format);
        DataUtils::get($node)->set('value', $node->value);
    }
}