<?php
namespace ide\commands;

use ide\build\AbstractBuildType;
use ide\editors\AbstractEditor;
use ide\forms\BuildProjectForm;
use ide\misc\AbstractCommand;
use php\lang\IllegalArgumentException;

class BuildProjectCommand extends AbstractCommand
{
    /** @var array */
    protected $buildTypes = [];

    public function getName()
    {
        return 'Собрать проект';
    }

    public function getIcon()
    {
        return 'icons/boxArrow16.png';
    }

    public function getCategory()
    {
        return 'run';
    }

    public function makeUiForHead()
    {
        $button = $this->makeGlyphButton();
        return $button;
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $dialog = new BuildProjectForm();
        $dialog->setBuildTypes($this->buildTypes);
        $dialog->showAndWait();
    }

    public function register($any)
    {
        if ($any instanceof AbstractBuildType) {
            $this->buildTypes[get_class($any)] = $any;
        } else {
            throw new IllegalArgumentException();
        }
    }
}