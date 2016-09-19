<?php
namespace ide\editors\form;

use ide\editors\value\ElementPropertyEditor;
use ide\misc\EventHandlerBehaviour;
use php\gui\designer\UXDesignProperties;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXLabel;
use php\gui\UXNode;

class IdePropertiesPane
{
    use EventHandlerBehaviour;

    /**
     * @var UXVBox
     */
    protected $ui;

    /**
     * @var UXNode
     */
    protected $emptyNode;

    /**
     * @var UXNode
     */
    protected $onlyNode;

    /**
     * @var UXDesignProperties[]
     */
    protected $properties = [];

    /**
     * IdePropertiesPane constructor.
     */
    public function __construct()
    {
        $this->emptyNode = new UXLabel('Свойств нет.');
    }

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
        if ($this->onlyNode) {
            $this->ui->remove($this->onlyNode);
            $this->onlyNode = null;
        }

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

    public function setEmptyNode(UXNode $node)
    {
        $this->emptyNode = $node;
    }

    public function setOnlyNode(UXNode $node)
    {
        $box = new UXHBox([$node]);
        $box->padding = 10;

        $this->ui->children->setAll([$box]);
        $this->onlyNode = $box;
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
        } else {
            $this->setOnlyNode($this->emptyNode);
        }
    }
}