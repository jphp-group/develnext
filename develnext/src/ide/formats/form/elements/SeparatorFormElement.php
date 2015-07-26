<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\shape\UXCircle;
use php\gui\UXNode;
use php\gui\UXProgressBar;
use php\gui\UXSeparator;

/**
 * @package ide\formats\form
 */
class SeparatorFormElement extends AbstractFormElement
{
    public function getName()
    {
        return 'Разделитель';
    }

    public function getIcon()
    {
        return 'icons/separator16.png';
    }

    public function getIdPattern()
    {
        return "separator%s";
    }

    public function getGroup()
    {
        return 'Дополнительно';
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $element = new UXSeparator();
        return $element;
    }

    public function getDefaultSize()
    {
        return [150, 15];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXSeparator;
    }
}
