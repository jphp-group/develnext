<?php
namespace ide\formats;

use ide\editors\AbstractEditor;
use ide\editors\GameSceneEditor;
use ide\formats\form\context\DeleteMenuCommand;
use ide\formats\form\context\SelectAllMenuCommand;
use ide\formats\form\elements\AnchorPaneFormElement;
use ide\formats\form\elements\FormFormElement;
use ide\formats\form\elements\GameObjectFormElement;
use ide\formats\form\tags\AnchorPaneFormElementTag;
use ide\formats\form\tags\DataFormElementTag;
use ide\formats\form\tags\NodeFormElementTag;
use ide\formats\templates\GuiFormFileTemplate;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\Project;
use ide\systems\FileSystem;
use ide\utils\FileUtils;
use php\gui\UXDialog;
use php\io\File;
use php\lib\Str;

class GameSceneFormat extends GuiFormFormat
{
    function __construct()
    {
        $this->requireFormat(new PhpCodeFormat());

        // Element types.

        $this->register(new AnchorPaneFormElement());
        $this->register(new FormFormElement());

        // Element tags.
        $this->register(new NodeFormElementTag());
        $this->register(new DataFormElementTag());
        $this->register(new AnchorPaneFormElementTag());

        // Context Menu.
        $this->register(new SelectAllMenuCommand());
        $this->register(new DeleteMenuCommand());

        $this->registerDone();
    }

    public function getIcon()
    {
        return 'icons/map16.png';
    }

    public function isValid($file)
    {
        $project = Ide::get()->getOpenedProject();

        if ($project) {
            $path = $project->getFile(GuiFrameworkProjectBehaviour::GAME_DIRECTORY);

            if (!Str::endsWith($file, ".fxml")) {
                return false;
            }

            return Str::startsWith(File::of($file)->getPath(), $path->getPath());
        }

        return false;
    }

    /**
     * @param $file
     *
     * @return AbstractEditor
     */
    public function createEditor($file)
    {
        return new GameSceneEditor($file, new GuiFormDumper($this->formElementTags));
    }

    /**
     * @param Project $project
     * @param $file
     * @param array $options
     * @return \ide\project\ProjectFile
     */
    public function createBlank(Project $project, $file, array $options)
    {
        return $project->createFile("src/.game/scenes/$file.fxml", new GuiFormFileTemplate());
    }

    /**
     * @return \ide\misc\SimpleSingleCommand
     */
    public function createBlankCommand()
    {
        return AbstractCommand::makeWithText('Новая игровая сцена', 'icons/gameMonitor16.png', function () {
            $project = Ide::project();

            if ($project) {
                $name = UXDialog::input('Придумайте название для игровой сцены');

                if ($name !== null) {
                    $file = $this->createBlank($project, $name, []);
                    FileSystem::open($file);
                }
            }
        });
    }
}