<?php
namespace ide\commands;

use Dialog;
use Files;
use ide\editors\AbstractEditor;
use ide\forms\BuildProgressForm;
use ide\forms\InputMessageBoxForm;
use ide\forms\MessageBoxForm;
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

class CreateScriptModuleProjectCommand extends AbstractCommand
{
    public function getName()
    {
        return 'Новый модуль';
    }

    public function getIcon()
    {
        return 'icons/blocks16.png';
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
            $dialog = new InputMessageBoxForm('Создание нового модуля', 'Введите название для нового модуля', '* Только латинские буквы, цифры и _');
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

                if ($guiBehaviour->hasModule($name)) {
                    $dialog = new MessageBoxForm("Модуль '$name' уже существует, хотите его пересоздать?", ['Нет, оставить', 'Да, пересоздать']);
                    if ($dialog->showDialog() && $dialog->getResultIndex() == 0) {
                        return null;
                    }
                }

                $file = $guiBehaviour->createModule($name);
                FileSystem::open($file);

                return $name;
            }
        }
    }
}