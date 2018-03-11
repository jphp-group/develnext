<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\UXButton;
use php\gui\UXHyperlink;
use php\gui\UXSeparator;
use php\gui\UXSlider;
use php\xml\DomElement;

class SliderFormElementTag extends AbstractFormElementTag
{
    
    public function getTagName()
    {
        return 'Slider';
    }

    public function getElementClass()
    {
        return UXSlider::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXSlider $node */
        $element->setAttribute('orientation', $node->orientation);

        $element->setAttribute('min', $node->min);
        $element->setAttribute('max', $node->max);
        $element->setAttribute('value', $node->value);
        $element->setAttribute('blockIncrement', $node->blockIncrement);

        $element->setAttribute('majorTickUnit', (double) $node->majorTickUnit);

        $element->setAttribute('showTickLabels', $node->showTickLabels ? 'true' : 'false');
        $element->setAttribute('showTickMarks', $node->showTickMarks ? 'true' : 'false');
        $element->setAttribute('snapToTicks', $node->snapToTicks ? 'true' : 'false');
    }
}