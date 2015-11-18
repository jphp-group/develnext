<?php
namespace ide\commands;

use ide\editors\menu\AbstractMenuCommand;
use ide\forms\MessageBoxForm;
use ide\forms\SaveProjectForLibraryForm;
use ide\Ide;
use ide\library\IdeLibraryProjectResource;
use ide\misc\AbstractCommand;
use ide\project\Project;
use php\gui\UXDialog;

class SaveProjectForLibraryCommand extends AbstractCommand
{
    public function getName()
    {
        return 'Сохранить проект в библиотеке';
    }

    public function getIcon()
    {
        return 'icons/library16.png';
    }

    public function isAlways()
    {
        return true;
    }

    public function onExecute()
    {
        $project = Ide::project();

        if ($project) {
            $dialog = new SaveProjectForLibraryForm();

            retry:
            $dialog->setResult($project);

            if ($dialog->showDialog()) {
                $result = $dialog->getResult();

                /** @var IdeLibraryProjectResource $resource */
                $resource = Ide::get()->getLibrary()->makeResource('projects', $result['name'] . ".zip");

                if (!$resource) {
                    $msg = new MessageBoxForm('Проект с таким названием уже есть в вашей библиотеке, хотите его перезаписать?', ['yes' => 'Да, перезаписать', 'no' => 'Нет']);

                    if ($msg->showDialog()) {
                        if ($msg->getResultIndex() == 1) {
                            goto retry;
                        }

                        $resource = Ide::get()->getLibrary()->makeResource('projects', $result['name'] . ".zip", true);
                    }
                }

                if ($resource) {
                    $resource->setName($result['name']);
                    $resource->setDescription($result['description']);
                    $project->export($resource->getPath());
                    $resource->save();

                    Ide::get()->getLibrary()->update();

                    Ide::toast('Проект был успешно сохранен в библиотеке');
                } else {
                    Ide::toast('Ошибка, невозможно сохранить проект в библиотеке!');
                }
            }
        } else {
            UXDialog::show('Создайте новый проект, чтобы сохранить его в библиотеке');
        }
    }
}