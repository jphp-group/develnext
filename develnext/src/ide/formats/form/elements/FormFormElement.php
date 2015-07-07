<?php
namespace ide\formats\form\elements;

use ide\editors\FormEditor;
use ide\formats\form\AbstractFormElement;
use php\gui\designer\UXDesignProperties;
use php\gui\UXNode;

/**
 * Class FormFormElement
 * @package ide\formats\form\elements
 */
class FormFormElement extends AbstractFormElement
{
    public function isOrigin($any)
    {
        return $any instanceof FormEditor;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return null;
    }

    public function getTarget($node)
    {
        if ($node instanceof FormEditor) {
            $layout = $node->getLayout();
            $layout->userData = $node;
            return $layout;
        }

        return $node;
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        return null;
    }

    public function createProperties(UXDesignProperties $properties)
    {
        parent::createProperties($properties);
    }
}