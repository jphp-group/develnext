<?php
namespace ide\forms;

use ide\Ide;
use ide\project\templates\DefaultGuiProjectTemplate;
use ide\systems\FileSystem;
use ide\systems\ProjectSystem;
use ide\systems\WatcherSystem;
use php\gui\designer\UXDesigner;
use php\gui\framework\AbstractForm;
use php\gui\layout\UXVBox;
use php\gui\UXButton;
use php\gui\UXForm;
use php\gui\UXImage;
use php\gui\UXImageView;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\gui\UXTreeView;

/**
 * @property UXTabPane $fileTabPane
 * @property UXTabPane $projectTabs
 * @property UXVBox $properties
 * @property UXTreeView $projectTree
 */
class MainForm extends AbstractForm
{
    public function show()
    {
        parent::show();
        $this->maximized = true;

        ProjectSystem::create(new DefaultGuiProjectTemplate(), 'e:/dn/project1.dnproject');
        ProjectSystem::save();
    }

    /**
     * @event close
     */
    public function doClose()
    {
        WatcherSystem::shutdown();
    }

    /**
     * @return UXVBox
     */
    public function getPropertiesPane()
    {
        return $this->properties;
    }

    /**
     * @return UXTreeView
     */
    public function getProjectTree()
    {
        return $this->projectTree;
    }
}