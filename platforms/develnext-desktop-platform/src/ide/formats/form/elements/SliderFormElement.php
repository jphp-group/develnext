<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\shape\UXCircle;
use php\gui\UXNode;
use php\gui\UXProgressBar;
use php\gui\UXSeparator;
use php\gui\UXSlider;

/**
 * @package ide\formats\form
 */
class SliderFormElement extends AbstractFormElement
{
    public function getName()
    {
        return 'Ползунок';
    }

    public function getElementClass()
    {
        return UXSlider::class;
    }

    public function getIcon()
    {
        return 'icons/slider16.png';
    }

    public function getIdPattern()
    {
        return "slider%s";
    }

    public function getGroup()
    {
        return 'Главное';
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $element = new UXSlider();
        return $element;
    }

    public function getDefaultSize()
    {
        return [150, 15];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXSlider;
    }
}
