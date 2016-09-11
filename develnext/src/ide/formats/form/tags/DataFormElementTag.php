<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\UXData;
use php\xml\DomElement;

class DataFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'Data';
    }

    public function isFinal()
    {
        return true;
    }

    public function getElementClass()
    {
        return UXData::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXData $node */
        $element->setAttribute('id', $node->id);

        foreach ($node->toArray() as $key => $value) {
            $element->setAttribute($key, self::escapeText($value));
        }
    }
}