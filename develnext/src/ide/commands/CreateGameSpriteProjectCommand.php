<?php
namespace ide\commands;

use Dialog;
use Files;
use ide\editors\AbstractEditor;
use ide\forms\BuildProgressForm;
use ide\forms\InputMessageBoxForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\project\behaviours\GradleProjectBehaviour;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\systems\FileSystem;
use ide\utils\FileUtils;
use php\gui\UXDialog;
use php\io\File;
use php\lang\Process;
use php\lib\Str;
use php\time\Time;
use php\util\Regex;

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
            $dialog = new InputMessageBoxForm('Создание нового спрайта', 'Введите название для нового спрайта', '* Только латинские буквы, цифры и _');
            $dialog->setPattern(new Regex('^[a-z\\_]{1}[a-z0-9\\_]{0,60}$', 'i'), 'Данное название некорректное');

            $dialog->showDialog();
            $name = $dialog->getResult();

            if ($name !== null) {
                $name = str::trim($name);

                if (!FileUtils::validate($name)) {
                    return null;
                }

                /** @var GuiFrameworkProjectBehaviour $guiBehaviour */
                $guiBehaviour = $project->getBehaviour(GuiFrameworkProjectBehaviour::class);

                if ($guiBehaviour->getSpriteManager()->get($name)) {
                    Dialog::error('Спрайт с таким названием уже существует в проекте');
                    $this->onExecute();
                    return null;
                }

                $file = $guiBehaviour->createSprite($name);
                FileSystem::open($file);

                return $name;
            }
        }
    }
}