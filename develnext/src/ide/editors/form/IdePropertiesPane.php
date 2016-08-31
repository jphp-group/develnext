<?php
namespace ide\editors\form;

use ide\editors\value\ElementPropertyEditor;
use ide\misc\EventHandlerBehaviour;
use php\gui\designer\UXDesignProperties;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXVBox;
use php\gui\UXNode;

class IdePropertiesPane
{
    use EventHandlerBehaviour;

    /**
     * @var UXVBox
     */
    protected $ui;

    /**
     * @var UXDesignProperties[]
     */
    protected $properties = [];

    /**
     * @return UXVBox
     */
    public function makeUi()
    {
        $ui = new UXVBox();
        $ui->spacing = 2;

        return $this->ui = $ui;
    }

    /**
     * @param UXNode $node
     */
    public function addCustomNode(UXNode $node)
    {
        $this->ui->children->insert(0, $node);
    }

    public function clearProperties()
    {
        if ($this->properties) {
            foreach ($this->properties as $properties) {
                foreach ($properties->getGroupPanes() as $pane) {
                    $this->ui->remove($pane);
                }
            }
        }

        $this->properties = [];
    }

    /**
     * @param UXDesignProperties|null $properties
     */
    public function setProperties($properties)
    {
        $this->clearProperties();

        if ($properties) {
            $properties->onChange(function () {
                $this->trigger('change');
            });

            $this->addProperties($properties);
        }
    }

    public function addProperties(UXDesignProperties $properties = null)
    {
        if ($properties) {
            $this->properties[] = $properties;

            $properties->onChange(function () {
                $this->trigger('change');
            });

            $this->ui->children->addAll($properties->getGroupPanes());
        }
    }

    public function update($target, array $properties = null)
    {
        if ($this->properties) {
            foreach ($this->properties as $group) {
                $group->target = $target;

                if ($properties) {
                    foreach ($properties as $one) {
                        $editor = $group->getEditorByCode($one);

                        if ($editor instanceof ElementPropertyEditor) {
                            $editor->updateUi($editor->getValue(), true);
                        }
                    }
                } else {
                    $group->update();
                }
            }
        }
    }
}