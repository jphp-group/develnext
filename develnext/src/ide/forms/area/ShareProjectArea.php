<?php

namespace ide\forms\area;

use ide\account\api\ServiceResponse;
use ide\forms\MessageBoxForm;
use ide\forms\SharedProjectDetailForm;
use ide\Ide;
use ide\Logger;
use ide\ui\Notifications;
use ide\utils\TimeUtils;
use php\gui\framework\AbstractFormArea;
use php\gui\layout\UXVBox;
use php\gui\UXCheckbox;
use php\gui\UXClipboard;
use php\gui\UXHyperlink;
use php\gui\UXLabel;
use php\gui\UXNode;
use php\lib\str;

/**
 * Class SyncProjectArea
 * @package ide\forms\area
 *
 * @property UXVBox $content
 * @property UXHyperlink $urlLink
 * @property UXLabel $updatedAtLabel
 * @property UXNode $syncPane
 * @property UXCheckbox $autoSyncCheckbox
 * @property UXNode $nonSyncPane
 */
class ShareProjectArea extends AbstractFormArea
{
    protected $_syncPane;
    protected $_nonSyncPane;

    protected $data;

    /**
     * @var callable
     */
    protected $doRefresh;

    public function __construct(callable $refreshCallback)
    {
        parent::__construct();
        $this->doRefresh = $refreshCallback;

        $this->_syncPane = $this->syncPane;
        $this->_nonSyncPane = $this->nonSyncPane;

        $this->urlLink->on('action', function () {
            browse($this->urlLink->text);
        });
    }

    public function setData(array $data = null)
    {
        $this->data = $data;
        $this->_nonSyncPane->free();
        $this->_syncPane->free();

        if ($data) {
            $canWrite = Ide::accountManager()->getAccountData()['id'] == $this->data['owner'];

            if ($canWrite) {
                $this->content->add($this->_syncPane);

                $this->updatedAtLabel->text = TimeUtils::getUpdateAt($data['updatedAt']);
                $this->setUrl($data['sharedUrl']);
            } else {
                $this->content->add($this->_nonSyncPane);
            }
        } else {
            $this->content->add($this->_nonSyncPane);
        }
    }

    public function setAutoSync($value)
    {
        if ($this->autoSyncCheckbox) {
            $this->autoSyncCheckbox->selected = $value;
        }
    }

    public function setUrl($url)
    {
        $this->urlLink->text = $url;
    }

    /**
     * @event copyButton.action
     */
    public function doCopyButtonAction()
    {
        UXClipboard::setText($this->urlLink->text);
        Ide::get()->toast('Ссылка успешно скопирована.');
    }

    /**
     * @event urlLink.action
     */
    public function doUrlLinkAction()
    {
        Ide::get()->toast('Opening ' . $this->urlLink->text . ' ...');
        browse($this->urlLink->text);
    }

    public function reUpload($silent = false)
    {
        $project = Ide::project();
        $project->save();

        $this->showPreloader('Загружаем проект на hub.develnext.org ...');

        $file = Ide::get()->createTempFile('.zip');

        $project->export($file);

        $res = Ide::service()->projectArchive()->updateAsync($this->data['id'], $project->getName(), '', null);
        $res->on('success', function (ServiceResponse $res) use ($file, $project, $silent) {
            $this->setData($res->data());
            Ide::project()->getIdeServiceConfig()->set('projectArchive.uid', $res->result('uid'));

            Ide::service()->projectArchive()->uploadArchiveAsync($res->result('id'), $file, function (ServiceResponse $response) use ($project, $silent) {
                if ($response->isSuccess()) {
                    $this->setData($response->result());

                    if (!$silent) {
                        $this->hidePreloader();
                        $this->refresh();

                        uiLater(function () {
                            Notifications::show('Изменения загружены', 'Измненения в проекте были успешно загружены на hub.develnext.org');
                        });
                    }
                } else {
                    $this->hidePreloader();
                    if (!$silent) {
                        list($message, $arg) = str::split($response->result(), ':');

                        if ($message === 'FileSizeLimit') {
                            $mb = round($arg / 1024 / 1024, 2);
                            Notifications::warning('Проект не загружен', "Проект слишком большой для загрузки, максимум разрешено $mb mb!");
                        } else {
                            if ($response->isAccessDenied()) {
                                Notifications::warning('Доступ запрещен', 'У вас нет доступа на запись к этому проекту, попробуйте его загрузить по новой.');
                            } else if ($response->isNotFound()) {
                                Notifications::warning('Проект не найден', 'По неясной причине проект не был найден, попробуйте его загрузить по новой.');
                            } else {
                                Notifications::error('Проект не загружен', 'Произошла непредвиденная ошибка, возможно сервис временно недоступен, попробуйте позже.');
                            }
                        }
                    }
                }
            });
        });

        $res->on('fail', function (ServiceResponse $res) use ($silent) {
            $this->hidePreloader();

            if (!$silent) {
                if ($res->isConflict()) {
                    Notifications::error('Проект не загружен', 'У вас уже есть проект с таким именем, измените название проекта.');
                } else {
                    Notifications::error('Проект не загружен', 'Произошла непредвиденная ошибка, возможно сервис временно недоступен, попробуйте позже.');
                }
            }
        });
    }

    /**
     * @event uploadButton.action
     */
    public function doReUploadButtonAction()
    {
        if (!MessageBoxForm::confirm('Вы точно хотите загрузить изменения в проекте на develnext.org?', $this)) {
            return;
        }

        $this->reUpload();
    }

    /**
     * @event сontrolButton.action
     */
    public function doControlAction()
    {
        $form = new SharedProjectDetailForm($this->data['uid']);
        $form->showAndWait();

        $this->refresh();
    }

    /**
     * ...
     */
    public function refresh()
    {
        $refresh = $this->doRefresh;
        $refresh();
    }

    /**
     * @event shareButton.action
     */
    public function doShareButtonAction()
    {
        if ($this->data['canWrite'] === false) {
            $this->doReUploadButtonAction();
            return;
        }

        if (!MessageBoxForm::confirm('Вы точно хотите загрузить проект на develnext.org?', $this)) {
            return;
        }

        $this->showPreloader('Сохраняем проект ...');

        $file = Ide::get()->createTempFile('.zip');

        $project = Ide::project();

        $project->export($file);

        $this->showPreloader('Загружаем проект на hub.develnext.org ...');

        $failedCallback = function (ServiceResponse $response) {
            if ($response->isConflict()) {
                Notifications::error('Проект не загружен', 'У вас уже есть проект с таким именем, измените название проекта.');
            } else {
                list($message, $arg) = str::split($response->result(), ':');

                switch ($message) {
                    case 'FileSizeLimit':
                        $mb = round($arg / 1024 / 1024, 2);
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
            }
            
            $this->hidePreloader();
        };

        $response = Ide::service()->projectArchive()->createAsync($project->getName(), '', null);
        $response->on('fail', $failedCallback);
        $response->on('success', function (ServiceResponse $response) use ($file, $failedCallback) {
            $projectId = $response->result('id');
            $projectUid = $response->result('uid');

            $response = Ide::service()->projectArchive()->uploadArchiveAsync($projectId, $file, null);
            $response->on('fail', $failedCallback);

            $response->on('success', function (ServiceResponse $response) use ($projectUid, $failedCallback) {
                Notifications::show('Проект загружен', 'Ваш проект был успешно загружен и опубликован на hub.develnext.org', 'SUCCESS');

                Ide::project()->getIdeServiceConfig()->set("projectArchive.uid", $projectUid);

                uiLater(function () use ($response) {
                    $this->hidePreloader();
                    $this->setData($response->result());
                });
            });
        });
    }
}