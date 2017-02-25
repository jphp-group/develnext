<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\gui\layout\UXFragmentPane;
use php\gui\layout\UXPanel;
use php\xml\DomDocument;
use php\xml\DomElement;

class FragmentFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'FragmentPane';
    }

    public function getElementClass()
    {
        return UXFragmentPane::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXPanel $node */
        $element->setAttribute('prefWidth', $node->size[0]);
        $element->setAttribute('prefHeight', $node->size[1]);

        $element->setAttribute('layoutX', $node->x);
        $element->setAttribute('layoutY', $node->y);

        $element->removeAttribute('style');
    }

    public function writeContent($node, DomElement $element, DomDocument $document, AbstractFormDumper $dumper)
    {
        $children = $element->find('children');

        if ($children) {
            $element->removeChild($children);
        }
    }
}