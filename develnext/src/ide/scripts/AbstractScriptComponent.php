<?php
namespace ide\scripts;
use ide\editors\form\FormNamedBlock;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\FormElementConfig;
use ide\Ide;
use ide\misc\GradleBuildConfig;
use php\gui\designer\UXDesignProperties;
use php\gui\layout\UXAnchorPane;
use php\gui\UXApplication;
use php\gui\UXLabel;
use php\gui\UXNode;

/**
 * Class AbstractScriptComponent
 * @package ide\scripts
 */
abstract class AbstractScriptComponent extends AbstractFormElement
{
    /**
     * @return string
     */
    abstract public function getType();

    /**
     * @return string
     */
    final public function getElementClass()
    {
        return $this->getType();
    }

    public function applyProperties(UXDesignProperties $properties)
    {
        if ($this->config) {
            foreach ($this->config->getPropertyGroups() as $code => $group) {
                $properties->addGroup($code, $group['title']);
            }

            foreach ($this->config->getProperties() as $code => $property) {
                $editorFactory = $property['editorFactory'];
                $editor = $editorFactory();

                if ($editor) {
                    $properties->addProperty($property['group'], $property['code'], $property['name'], $editor);
                }
            }
        }
    }

    /**
     * @return null|string|UXImage
     */
    public function getIcon()
    {
        return null;
    }

    abstract public function getDescription();

    public function getPlaceholder(ScriptComponentContainer $container)
    {
        return $this->getDescription();
    }

    public function getTarget($node)
    {
        if ($node->userData instanceof ScriptComponentContainer) {
            return $node->userData;
        }

        return parent::getTarget($node);
    }


    /**
     * @return FormNamedBlock
     */
    public function createElement()
    {
        $block = new FormNamedBlock($this->getName(), $this->getIcon());
        return $block;
    }
}