<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\designer\UXDesignProperties;
use php\gui\UXNode;
use php\gui\UXTextArea;
use php\gui\UXTextField;

/**
 * Class TextAreaFormElement
 * @package ide\formats\form
 */
class TextAreaFormElement extends AbstractFormElement
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Многострочное поле';
    }

    public function getElementClass()
    {
        return UXTextArea::class;
    }

    public function getIcon()
    {
        return 'icons/textArea16.png';
    }

    public function getIdPattern()
    {
        return "textArea%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $element = new UXTextArea();
        return $element;
    }

    public function getDefaultSize()
    {
        return [150, 60];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXTextArea;
    }

    public function resetStyle(UXNode $node, UXNode $baseNode)
    {
        parent::resetStyle($node, $baseNode);

        /** @var UXTextField $node */
        /** @var UXTextField $baseNode */
        $node->font = $baseNode->font;
    }


}