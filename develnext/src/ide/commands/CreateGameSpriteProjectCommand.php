<?php
namespace ide\commands;

use Dialog;
use Files;
use ide\editors\AbstractEditor;
use ide\forms\BuildProgressForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\project\behaviours\GradleProjectBehaviour;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\systems\FileSystem;
use php\gui\framework\Timer;
use php\gui\UXDialog;
use php\io\File;
use php\lang\Process;
use php\lib\Str;
use php\time\Time;

class CreateGameSpriteProjectCommand extends AbstractCommand
{
    public function getName()
    {
        return 'Новый спрайт';
    }

    public function getIcon()
    {
        return 'icons/picture16.png';
    }

    public function getCategory()
    {
        return 'create';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $ide = Ide::get();
        $project = $ide->getOpenedProject();

        if ($project) {
            $name = UXDialog::input('Придумайте название для спрайта');

            if ($name !== null) {
                /** @var GuiFrameworkProjectBehaviour $guiBehaviour */
                $guiBehaviour = $project->getBehaviour(GuiFrameworkProjectBehaviour::class);

                if ($guiBehaviour->getSpriteManager()->get($name)) {
                    Dialog::error('Спрайт с таким названием уже существует в проекте');
                    $this->onExecute();
                    return;
                }

                $file = $guiBehaviour->createSprite($name);
                FileSystem::open($file);

                return $name;
            }
        }
    }
}