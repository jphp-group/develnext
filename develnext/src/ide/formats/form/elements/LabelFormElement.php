<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\designer\UXDesignProperties;
use php\gui\UXLabel;
use php\gui\UXNode;

class LabelFormElement extends AbstractFormElement
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Текст';
    }

    public function getIcon()
    {
        return 'icons/label16.png';
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $element = new UXLabel($this->getName());
        return $element;
    }

    public function getDefaultSize()
    {
        return [100, 20];
    }

    /**
     * @param UXDesignProperties $properties
     */
    public function createProperties(UXDesignProperties $properties)
    {
        // TODO: Implement createProperties() method.
    }

    public function isOrigin($any)
    {
        return $any instanceof UXLabel;
    }
}