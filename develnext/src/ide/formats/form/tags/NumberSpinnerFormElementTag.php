<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\UXNumberSpinner;
use php\gui\UXPasswordField;
use php\gui\UXTextField;
use php\xml\DomElement;

class NumberSpinnerFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'org.develnext.jphp.ext.javafx.support.control.NumberSpinner';
    }

    public function getElementClass()
    {
        return UXNumberSpinner::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXNumberSpinner $node */
        $element->setAttribute('editable', $node->editable ? 'true' : 'false');
        $element->setAttribute('min', $node->min);
        $element->setAttribute('max', $node->max);
        $element->setAttribute('step', $node->step);
        $element->setAttribute('initial', $node->initial);
        $element->setAttribute('alignment', $node->alignment);
    }
}