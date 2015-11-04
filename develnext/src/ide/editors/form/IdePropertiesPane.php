<?php
namespace ide\editors\form;

use php\gui\designer\UXDesignProperties;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXVBox;
use php\gui\UXNode;

class IdePropertiesPane
{
    /**
     * @var UXVBox
     */
    protected $ui;

    /**
     * @var UXDesignProperties
     */
    protected $properties;

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

    /**
     * @param UXDesignProperties|null $properties
     */
    public function setProperties($properties)
    {
        if ($this->properties) {
            foreach ($this->properties->getGroupPanes() as $pane) {
                $this->ui->remove($pane);
            }
        }

        $this->properties = $properties;

        if ($properties) {
            foreach ($this->properties->getGroupPanes() as $pane) {
                $this->ui->add($pane);
            }
        }
    }

    public function update($target)
    {
        if ($this->properties) {
            $this->properties->target = $target;

            $this->properties->update();
        }
    }
}