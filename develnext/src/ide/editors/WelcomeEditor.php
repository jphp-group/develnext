<?php
namespace ide\editors;

use ide\commands\NewProjectCommand;
use ide\commands\OpenProjectCommand;
use ide\forms\OpenProjectForm;
use ide\Ide;
use php\gui\UXLoader;
use php\gui\UXNode;

class WelcomeEditor extends AbstractEditor
{
    public function isCloseable()
    {
        return false;
    }

    public function getTitle()
    {
        return 'Добро пожаловать';
    }

    public function isAutoClose()
    {
        return false;
    }

    public function load()
    {
        // nop.
    }

    public function save()
    {
        // nop.
    }

    /**
     * @return UXNode
     */
    public function makeUi()
    {
        $loader = new UXLoader();

        $layout = $loader->load('res://.forms/blocks/_Welcome.fxml');

        $layout->lookup('#createProjectButton')->on('click', function () {
            Ide::get()->executeCommand(NewProjectCommand::class);
        });

        $layout->lookup('#openProjectButton')->on('click', function () {
            Ide::get()->executeCommand(OpenProjectCommand::class);
        });

        return $layout;
    }
}