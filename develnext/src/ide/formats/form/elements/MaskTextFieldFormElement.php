<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\designer\UXDesignProperties;
use php\gui\UXMaskTextField;
use php\gui\UXNode;
use php\gui\UXTextField;

/**
 * @package ide\formats\form
 */
class MaskTextFieldFormElement extends AbstractFormElement
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Поле с маской';
    }

    public function getGroup()
    {
        return 'Дополнительно';
    }

    public function getElementClass()
    {
        return UXMaskTextField::class;
    }

    public function getIcon()
    {
        return 'icons/maskTextField16.png';
    }

    public function getIdPattern()
    {
        return "edit%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $element = new UXMaskTextField();
        return $element;
    }

    public function getDefaultSize()
    {
        return [150, 35];
    }

    public function isOrigin($any)
    {
        return get_class($any) == UXMaskTextField::class;
    }
}