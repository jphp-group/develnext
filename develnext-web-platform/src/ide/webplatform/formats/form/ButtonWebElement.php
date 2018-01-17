<?php
namespace ide\webplatform\formats\form;

use php\gui\UXButton;
use php\gui\UXNode;

class ButtonWebElement extends AbstractWebElement
{
    public function isOrigin($any)
    {
        return $any instanceof UXButton && $any->classes->has('ux-button');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Кнопка';
    }

    public function getIcon()
    {
        return 'icons/button16.png';
    }

    public function getIdPattern()
    {
        return "button%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $btn = new UXButton($this->getName());
        $btn->classes->add('ux-button');
        return $btn;
    }
}