<?php
namespace ide\formats\form\elements;

use ide\editors\value\BooleanPropertyEditor;
use ide\editors\value\ColorPropertyEditor;
use ide\editors\value\FontPropertyEditor;
use ide\editors\value\IntegerPropertyEditor;
use ide\editors\value\PositionPropertyEditor;
use ide\editors\value\TextPropertyEditor;
use ide\formats\form\AbstractFormElement;
use php\gui\designer\UXDesignProperties;
use php\gui\UXLabel;
use php\gui\UXNode;

class LabelFormElement extends LabeledFormElement
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

    public function getIdPattern()
    {
        return "label%s";
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

    public function isOrigin($any)
    {
        return $any instanceof UXLabel;
    }
}