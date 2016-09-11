<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\UXNode;
use php\gui\UXProgressBar;
use php\gui\UXProgressIndicator;

/**
 * @package ide\formats\form
 */
class ProgressIndicatorFormElement extends AbstractFormElement
{
    public function getName()
    {
        return 'Индикатор загрузки';
    }

    public function getElementClass()
    {
        return UXProgressIndicator::class;
    }

    public function getGroup()
    {
        return 'Дополнительно';
    }

    public function getIcon()
    {
        return 'icons/progressIndicator16.png';
    }

    public function getIdPattern()
    {
        return "progressIndicator%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $element = new UXProgressIndicator();
        return $element;
    }

    public function getDefaultSize()
    {
        return [35, 35];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXProgressIndicator;
    }
}
