<?php
namespace ide\commands\account;


use ide\account\api\ServiceResponse;
use ide\Ide;
use ide\misc\AbstractCommand;
use php\gui\UXDialog;

class AccountProjectSyncCommand extends AbstractCommand
{
    public function getName()
    {
        return 'Сохранить проект в аккауте';
    }

    public function getCategory()
    {
        return 'account';
    }

    protected function showFail()
    {
        Ide::get()->getMainForm()->hidePreloader();
        UXDialog::show('Ошибка синхронизации, что-то пошло не так', 'ERROR');
    }

    public function onExecute()
    {
        $project = Ide::get()->getOpenedProject();
        $project->save();

        Ide::get()->getMainForm()->showPreloader('Обновление проекта ...');

        Ide::service()->project()->updateAsync($project, function (ServiceResponse $response) use ($project) {
            if ($response->isNotSuccess()) {
                $this->showFail();
                return;
            }

            Ide::get()->getMainForm()->showPreloader('Подготовка к загрузке ...');

            Ide::service()->project()->prepareSyncAsync($project, function (ServiceResponse $response) use ($project) {
                if ($response->isNotSuccess()) {
                    $this->showFail();
                    return;
                }

                Ide::get()->getMainForm()->showPreloader('Загрузка файлов ...');

                Ide::service()->project()->syncAsync($project, $response->data(), function (ServiceResponse $response) {
                    if ($response->isNotSuccess()) {
                        $this->showFail();
                        return;
                    }

                    Ide::get()->getMainForm()->toast('Проект успешно опубликован');

                    Ide::get()->getMainForm()->hidePreloader();
                });
            });
        });
    }
}