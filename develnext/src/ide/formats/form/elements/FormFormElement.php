<?php
namespace ide\formats\form\elements;

use ide\editors\FormEditor;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\scriptgen\LoadFragmentScriptGenerator;
use ide\library\IdeLibraryScriptGeneratorResource;
use php\gui\designer\UXDesignProperties;
use php\gui\framework\AbstractForm;
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

    public function getElementClass()
    {
        return AbstractForm::class;
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

    public function getScriptGenerators()
    {
        return [
            new IdeLibraryScriptGeneratorResource('res://.dn/bundle/uiDesktop/scriptgen/FadeOutFormNextScriptGen'),
            new IdeLibraryScriptGeneratorResource('res://.dn/bundle/uiDesktop/scriptgen/LoadInFragmentScriptGen')
        ];
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