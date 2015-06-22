<?php
namespace ide\forms;

use ide\Ide;
use ide\systems\FileSystem;
use php\gui\designer\UXDesigner;
use php\gui\framework\AbstractForm;
use php\gui\layout\UXVBox;
use php\gui\UXButton;
use php\gui\UXForm;
use php\gui\UXImage;
use php\gui\UXImageView;
use php\gui\UXTab;
use php\gui\UXTabPane;

/**
 * @property UXTabPane fileTabPane
 * @property UXTabPane $projectTabs
 * @property UXVBox $properties
 */
class MainForm extends AbstractForm
{
    public function show()
    {
        parent::show();
        $this->maximized = true;

        FileSystem::open('d:/sample.fxml');
    }

    /**
     * @return UXVBox
     */
    public function getPropertiesPane()
    {
        return $this->properties;
    }
}