<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\UXNode;
use php\gui\UXProgressBar;

/**
 * @package ide\formats\form
 */
class ProgressBarFormElement extends AbstractFormElement
{
    public function getName()
    {
        return 'Прогресс';
    }

    public function getIcon()
    {
        return 'icons/progressbar16.png';
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $element = new UXProgressBar();
        $element->progress = 0.5;
        return $element;
    }

    public function getDefaultSize()
    {
        return [150, 20];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXProgressBar;
    }
}
