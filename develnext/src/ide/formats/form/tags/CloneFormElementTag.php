<?php
namespace ide\formats\form\tags;


use ide\formats\form\AbstractFormElementTag;
use php\gui\UXCustomNode;
use php\gui\UXNode;
use php\xml\DomElement;

class CloneFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return "CustomNode";
    }

    public function getElementClass()
    {
        return null;
    }

    public function writeAttributes($node, DomElement $element)
    {
        if ($node instanceof UXCustomNode) {
            $element->setAttributes($node->toArray());
        } elseif ($node instanceof UXNode) {
            $type = $node->data('-factory-id');

            $element->setAttribute('type', $type);

            $element->setAttribute('x', $node->x);
            $element->setAttribute('y', $node->y);

            if ($node->data('disabled')) {
                $element->setAttribute('disabled', 1);
            }

            if ($node->data('hidden')) {
                $element->setAttribute('hidden', 1);
            }
        }
    }
}