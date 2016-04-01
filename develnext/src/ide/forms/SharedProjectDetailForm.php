<?php
namespace ide\forms;
use ide\commands\ShareProjectCommand;
use ide\Ide;
use ide\account\api\ServiceResponse;
use ide\Logger;
use ide\systems\ProjectSystem;
use ide\ui\Notifications;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXClipboard;
use php\gui\UXHyperlink;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\io\IOException;
use php\io\Stream;
use php\lang\Thread;
use php\time\Time;
use script\TimerScript;
use timer\AccurateTimer;

/**
 * Class SharedProjectDetailForm
 * @package ide\forms
 *
 * @property UXLabel $nameLabel
 * @property UXLabel $descriptionLabel
 * @property UXHyperlink $accountLink
 * @property UXHyperlink $urlLink
 * @property UXImageView $icon
 * @property UXLabel $dateLabel
 * @property UXButton $reuploadButton
 * @property UXButton $deleteButton
 * @property UXButton $openButton
 */
class SharedProjectDetailForm extends AbstractOnlineIdeForm
{
    /**
     * @var string
     */
    protected $uid;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var bool
     */
    protected $forUpload;

    /**
     * SharedProjectDetailForm constructor.
     * @param $uid
     * @param bool $forUpload
     */
    public function __construct($uid, $forUpload = false)
    {
        parent::__construct();
        $this->uid = $uid;

        if (Ide::project() && Ide::project()->getIdeServiceConfig()->get('projectArchive.uid') == $uid) {
            $forUpload = true;
        }

        if ($forUpload) {
            $this->openButton->free();
        } else {
            $this->deleteButton->free();
            $this->reuploadButton->free();
        }

        $this->forUpload = $forUpload;
    }

    public function isAuthRequired()
    {
        return $this->forUpload;
    }

    protected function init()
    {
        parent::init();
        $this->icon->image = ico('flatUpload32')->image;
    }

    public function update(array $data)
    {
        $this->data = $data;

        $this->nameLabel->text = $data['name'] ?: 'Неизвестный проект';
        //$this->descriptionLabel->text = $data['description'] ?: 'У данного проекта нет описания.';
        $this->urlLink->text = $data['shareUrl'] ? Ide::service()->getEndpoint() . $data['shareUrl'] : 'Неизвестная ссылка';
        $this->accountLink->text = $data['account']['name'] ?: 'Неизвестно';
        $this->dateLabel->text = $data['updatedAt'] ? (new Time($data['updatedAt']))->toString('dd.MM.yyyy') : 'Неизвестно';

        if ($this->deleteButton) {
            $this->deleteButton->enabled = $data['canWrite'] || !isset($data['canWrite']);
        }

        if ($this->reuploadButton) {
            $this->reuploadButton->text = ($data['canWrite'] || !isset($data['canWrite'])) ? 'Загрузить' : 'Клонировать';
        }
    }

    /**
     * @event deleteButton.action
     */
    public function doDelete()
    {
        $msg = new MessageBoxForm('Вы уверены, что хотите удалить из общего доступа этот проект?', ['Да, удалить', 'Нет, отмена']);

        if ($msg->showDialog() && $msg->getResultIndex() == 1) {
            return;
        }

        $this->showPreloader('Подождите ...');

        Ide::service()->projectArchive()->deleteAsync($this->data['id'], false, function (ServiceResponse $response) {
            $this->hidePreloader();

            if ($response->isSuccess()) {
                $this->hide();

                Ide::project()->getIdeServiceConfig()->set('projectArchive.uid', null);

                Notifications::show('Проект удален', 'Ваш проект был успешно удален из общего доступа, при желании вы можете снова им поделиться.', 'SUCCESS');
            } else {
                Logger::warn("Unable to delete project with uid = {$this->data['uid']}, {$response->toLog()}");

                Notifications::error('Ошибка удаления', 'Мы не смогли удалить ваш проект, возможно сервис временно недоступен, попробуйте позже.');
                $this->deleteButton->enabled = false;

                AccurateTimer::executeAfter(2000, function () {
                    $this->deleteButton->enabled = true;
                });
            }
        });
    }

    /**
     * @event reuploadButton.action
     */
    public function doReupload()
    {
        if ($this->data['canWrite'] || !isset($this->data['canWrite'])) {
            $msg = new MessageBoxForm('Вы уверены, что хотите перезаписать ранее загруженный проект? (ссылка останется прежней)', ['Да, перезалить', 'Нет, отмена']);

            if ($msg->showDialog() && $msg->getResultIndex() == 1) {
                return;
            }
        }

        $this->showPreloader('Сохраняем проект ...');

        $project = Ide::project();
        $project->save();

        $file = Ide::get()->createTempFile('.zip');

        $project->export($file);

        $this->showPreloader('Загружаем проект ...');

        Ide::service()->projectArchive()->uploadOldAsync($this->data['id'], $file, function (ServiceResponse $response) use ($project) {
            $this->hidePreloader();

            if ($response->isSuccess()) {
                $this->update($response->data());

                Ide::project()->getIdeServiceConfig()->set('projectArchive.uid', $response->data()['uid']);

                Ide::service()->projectArchive()->updateAsync($this->data['id'], $project->getName(), '', function (ServiceResponse $response) {
                    if ($response->isSuccess()) {
                        $this->update($response->data());
                    }
                });

                UXApplication::runLater(function () {
                    Notifications::show('Проект перезалит', 'Проект был успешно перезалит в общую базу проектов');
                    $this->reuploadButton->enabled = false;
                    /*$this->hide();
                    $dialog = new SharedProjectDetailForm($this->data['uid']);
                    $dialog->showAndWait(); */
                });
            } else {
                switch ($response->message()) {
                    case 'AccessDenied':
                        Notifications::warning('Доступ запрещен', 'У вас нет доступа на запись к этому проекту, попробуйте его загрузить по новой.');
                        $this->hide();
                        break;
                    case 'NotFound':
                        $this->hide();
                        Notifications::warning('Проект не найден', 'По неясной причине проект не был найден, попробуйте его загрузить по новой.');
                        break;
                    default:
                        Notifications::error('Проект не перезалит', 'Произошла непредвиденная ошибка, возможно сервис временно недоступен, попробуйте позже.');
                        break;
                }
            }
        });
    }

    /**
     * @event cancelButton.action
     */
    public function doCancel()
    {
        $this->hide();
    }

    /**
     * @event urlLink.action
     */
    public function doUrl()
    {
        $this->toast('Сейчас произойдет редирект ...');
        browse($this->urlLink->text);
    }

    /**
     * @event copyUrlButton.action
     */
    public function doCopyUrl()
    {
        ShareProjectCommand::setLastCheckUid($this->urlLink->text);
        UXClipboard::setText($this->urlLink->text);
        $this->toast('Ссылка успешно скопирована в буфер обмена');
    }

    /**
     * @event show
     */
    public function doShow()
    {
        if ($this->reuploadButton) {
            if (!Ide::accountManager()->isAuthorized()) {
                return;
            }

            $this->reuploadButton->enabled = true;
        }

        if ($this->openButton) {
            $this->openButton->enabled = true;
        }

        $this->showPreloader();

        Logger::info("Get project info, uid = {$this->uid} ...");

        Ide::service()->projectArchive()->getAsync($this->uid, function (ServiceResponse $response) {
            $this->hidePreloader();

            if ($response->isSuccess()) {
                $this->update($response->data());
            } else {
                switch ($response->message()) {
                    case 'NotFound':
                        Notifications::warning('Проект не найден', 'Запрашиваемый проект не найден в базе всех проектов сервиса.');

                        if ($this->forUpload) {
                            UXApplication::runLater(function () {
                                $dialog = new ShareProjectForm(true);
                                $dialog->showAndWait();
                            });
                        }
                        break;
                    case 'AccessDenied':
                        Notifications::warning('Проект закрыт', 'Доступ к запрашиваемому проекту ограничен, у вас нет прав доступа к нему.');

                        if ($this->forUpload) {
                            UXApplication::runLater(function () {
                                $dialog = new ShareProjectForm(true);
                                $dialog->showAndWait();
                            });
                        }
                        break;
                    default:
                        Logger::error("Unable to get project info with uid = $this->uid, {$response->toLog()}");
                        Notifications::error('Проект недоступен', 'Мы не смогли запросить этот проект, возможно сервис недоступен, попробуйте позже.');
                        break;
                }
                $this->hide();
            }
        });
    }

    /**
     * @event openButton.action
     */
    public function doOpen()
    {
        if ($this->data['shareUrl']) {
            $this->showPreloader('Загрузка файла проекта ...');

            $thread = (new Thread(function () {
                $name = $this->data['name'] ?: 'Unknown';
                $path = Ide::get()->getUserConfigValue('projectDirectory') . "/$name.zip";

                try {
                    if (Ide::service()->projectArchive()->downloadToFile($this->data['shareUrl'], $path)) {
                        UXApplication::runLater(function () use ($path) {
                            $this->hide();

                            UXApplication::runLater(function () use ($path) {
                                ProjectSystem::import($path, null, null, function () {
                                    Ide::project()->getIdeServiceConfig()->set('projectArchive.uid', $this->data['uid']);
                                });
                            });
                        });
                    } else {
                        UXApplication::runLater(function () {
                            $this->openButton->enabled = false;

                            Notifications::error('Ошибка загрузки', 'Данный проект невозможно загрузить, что-то пошло не так.');
                        });
                    }
                } catch (IOException $e) {
                    Logger::exception("Unable to open shared project with uid = {$this->data['uid']}", $e);

                    UXApplication::runLater(function () {
                        $this->openButton->enabled = false;

                        Notifications::error('Ошибка загрузки', 'Данный проект невозможно загрузить, что-то пошло не так.');
                    });
                } finally {
                    UXApplication::runLater(function () {
                        $this->hidePreloader();
                    });
                }

            }));
            $thread->setName("SharedProjectDetailOpen");
            $thread->start();
        } else {
            Notifications::error('Ошибка загрузки', 'Данный проект невозможно загрузить, что-то пошло не так.');
        }
    }
}