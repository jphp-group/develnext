<?php
namespace ide\scripts;
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
     * @param GradleBuildConfig $gradleBuild
     *
     * @return array
     */
    public function adaptForGradleBuild(GradleBuildConfig $gradleBuild)
    {
        $gradleBuild->setDependency('develnext-stdlib');
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

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $element = new UXAnchorPane();
        $element->maxSize = $element->minSize = $element->size = [32, 32];

        $element->style = '-fx-border-width: 1px; -fx-border-color: gray; -fx-background-color: silver; -fx-border-radius: 3px;';

        $icon = Ide::get()->getImage($this->getIcon());

        if ($icon) {
            $icon->mouseTransparent = true;
            $icon->position = [8, 8];
            $element->add($icon);
        }

        $label = new UXLabel($this->getName());
        $label->padding = [2, 4];
        $label->position = [0, 32 + 7];
        $label->style = '-fx-border-width: 1px; -fx-border-color: gray; -fx-background-color: white; -fx-border-radius: 3px;';

        $label->watch('text', function () use ($label) {
            UXApplication::runLater(function () use ($label) {
                $label->x = - $label->width / 2;
            });
        });

        $element->add($label);

        return $element;
    }
}