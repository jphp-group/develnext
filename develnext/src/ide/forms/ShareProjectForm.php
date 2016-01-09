<?php
namespace ide\forms;
use ide\account\api\ServiceResponse;
use ide\Logger;
use ide\systems\ProjectSystem;
use ide\Ide;
use ide\ui\Notifications;
use php\gui\event\UXEvent;
use php\gui\UXApplication;

/**
 * Class ShareProjectForm
 * @package ide\forms
 */
class ShareProjectForm extends AbstractOnlineIdeForm
{
    /**
     * @var bool
     */
    protected $ignoreUid;

    /**
     * ShareProjectForm constructor.
     * @param $ignoreUid
     */
    public function __construct($ignoreUid = false)
    {
        parent::__construct();

        $this->ignoreUid = $ignoreUid;
    }

    public function isAuthRequired()
    {
        return true;
    }

    /**
     * @event show
     */
    public function doShow()
    {
        $project = Ide::project();

        if (!$this->ignoreUid && $project->getIdeServiceConfig()->get('projectArchive.uid')) {
            UXApplication::runLater(function () use ($project) {
                $this->hide();

                UXApplication::runLater(function () use ($project) {
                    $dialog = new SharedProjectDetailForm($project->getIdeServiceConfig()->get('projectArchive.uid'), true);
                    $dialog->showAndWait();
                });
            });
        }
    }

    /**
     * @event cancelButton.action
     */
    public function doCancel()
    {
        $this->hide();
    }

    /**
     * @event shareButton.action
     */
    public function doShare()
    {
        $this->showPreloader('Сохраняем проект ...');

        $file = Ide::get()->createTempFile('.zip');

        $project = Ide::project();

        $project->export($file);

        $this->showPreloader('Загружаем проект на develnext.org ...');

        Ide::service()->projectArchive()->uploadNewAsync($file, function (ServiceResponse $response) use ($project) {
            if ($response->isSuccess()) {
                $data = $response->data();

                Ide::service()->projectArchive()->updateAsync($data['id'], $project->getName(), '', function (ServiceResponse $response) use ($data) {
                    $this->hidePreloader();

                    if ($response->isSuccess()) {
                        Notifications::show('Проект загружен', 'Ваш проект был успешно загружен и опубликован на develnext.org', 'SUCCESS');
                        $this->hide();

                        Ide::project()->getIdeServiceConfig()->set("projectArchive.uid", $data['uid']);

                        UXApplication::runLater(function () use ($response) {
                            $dialog = new SharedProjectDetailForm($response->data()['uid'], true);
                            $dialog->showAndWait();
                        });
                    } else {
                        Logger::error("Unable to update project {$response->toLog()}");
                        Notifications::error('Проект не загружен', 'Что-то пошло не так, попробуйте позже, возможно сервис недоступен.');
                    }
                });
            } else {
                switch ($response->message()) {
                    case 'FileSizeLimit':
                        $mb = round($response->data() / 1024 / 1024, 2);
                        Notifications::warning('Проект не загружен', "Проект слишком большой для загрузки, максимум разрешено $mb mb!");
                        break;
                    case 'LimitSpacePerDay':
                        Notifications::warning('Проект не загружен', "Вы исчерпали лимит загрузок на сегодня, попробуйте удалить большие ненужные проекты!");
                        break;
                    case 'CountLimit':
                        Notifications::warning('Проект не загружен', "Вы загрузили слишком много проектов, максимум разрешено {$response->data()} шт. в день!");
                        break;
                    default:
                        Logger::error("Unable to upload project {$response->toLog()}");
                        Notifications::error('Проект не загружен', 'Что-то пошло не так, попробуйте позже, возможно сервис недоступен.');
                }

                $this->hidePreloader();
            }
        });
    }
}