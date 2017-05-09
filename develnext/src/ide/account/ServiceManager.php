<?php
namespace ide\account;

use ide\account\api\AbstractService;
use ide\account\api\AccountService;
use ide\account\api\IconService;
use ide\account\api\FileService;
use ide\account\api\NoticeService;
use ide\account\api\ProfileService;
use ide\account\api\ProjectArchiveService;
use ide\account\api\ProjectService;
use ide\account\api\ServiceResponse;
use ide\Ide;
use ide\Logger;
use ide\misc\EventHandlerBehaviour;
use ide\ui\Notifications;
use ide\utils\Json;
use php\lang\IllegalArgumentException;
use php\lang\System;
use script\TimerScript;

/**
 * Class ServiceManager
 * @package ide\account
 */
class ServiceManager
{
    use EventHandlerBehaviour;

    protected $connectionOk = false;

    /**
     * @var string
     */
    protected $endpoint = 'https://api.develnext.org/';

    /**
     * @var array
     */
    protected $status = [
        'private' => '',
        'public'  => '',
    ];

    /**
     * @var AccountService
     */
    protected $accountService;

    /**
     * @var ProjectService
     */
    protected $profileService;

    /**
     * @var IdeService
     */
    protected $ideService;

    /**
     * @var ProjectService
     */
    protected $projectService;

    /**
     * @var ProjectArchiveService
     */
    protected $projectArchiveService;

    /**
     * @var NoticeService
     */
    protected $noticeService;

    /**
     * @var FileService
     */
    protected $fileService;

    /**
     * @var IconService
     */
    protected $iconService;

    /**
     * ServiceManager constructor.
     */
    public function __construct()
    {
        $this->accountService = new AccountService();
        $this->profileService = new ProfileService();
        $this->ideService = new IdeService();
        $this->projectService = new ProjectService();
        $this->projectArchiveService = new ProjectArchiveService();
        $this->noticeService = new NoticeService();
        $this->fileService = new FileService();
        $this->iconService = new IconService();

        $this->accountService->on('exception', function () { $this->updateStatus(); });
        $this->projectService->on('exception', function () { $this->updateStatus(); });
        $this->ideService->on('exception', function ($methodName) {
            if ($methodName != 'ide/status') {
                $this->updateStatus();
            }
        });

        $timer = new TimerScript(20 * 1000, true, [$this, 'updateStatus']);
        $timer->start();
    }

    protected function changeStatus($status)
    {
        if ($status['endpoint']) {
            $this->endpoint = $status['endpoint'];
        }

        if ($status['private'] != $this->status['private']) {
            $this->status['private'] = $status['private'];

            if ($status['private'] == 'ok') {
                $this->trigger('privateEnable');
            } else {
                $this->trigger('privateDisable');
            }

            $this->trigger('privateUpdate', [$status['private']]);
        }

        if ($status['public'] != $this->status['public']) {
            $this->status['public'] = $status['public'];

            if ($status['public'] == 'ok') {
                $this->trigger('publicEnable');
            } else {
                $this->trigger('publicDisable');
            }

            $this->trigger('publicUpdate');
        }
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        if ($sysEndpoint = System::getProperty('develnext.endpoint')) {
            return $sysEndpoint;
        }

        return $this->endpoint;
    }

    public function updateStatus()
    {
        if ($this->ideService) {
            if (Ide::get()->isIdle()) {
                Logger::info("Skip update status, ide in idle mode ...");
                return;
            }

            Logger::info("Update status, endpoint = {$this->getEndpoint()} ...");

            $this->ideService->statusAsync(function (ServiceResponse $response) {
                Logger::info("Update status response = {message: {$response->message()}, data: " . Json::encode($response->result()) . "}");

                if ($response->isConnectionRefused()) {
                    $this->changeStatus([
                        'private' => 'fail',
                        'public' => 'fail',
                    ]);

                    if ($this->connectionOk) {
                        $this->connectionOk = false;
                        $this->trigger('connectionFail', [$response]);
                    }
                } else {
                    $this->connectionOk = true;

                    if ($response->isSuccess()) {
                        $status = $response->result();
                        $this->changeStatus($status);
                    } else {
                        $this->changeStatus([
                            'private' => 'fail',
                            'public' => 'fail',
                        ]);
                    }
                }
            });
        }
    }

    /**
     * @return bool
     */
    public function canPrivate()
    {
        return $this->status['private'] == 'ok';
    }

    /**
     * @return bool
     */
    public function canPublic()
    {
        return $this->status['public'] == 'ok';
    }

    /**
     * @return AccountService
     */
    public function account()
    {
        return $this->accountService;
    }

    /**
     * @return FileService
     */
    public function file()
    {
        return $this->fileService;
    }

    /**
     * @return IdeService
     */
    public function ide()
    {
        return $this->ideService;
    }

    public function project()
    {
        return $this->projectService;
    }

    public function projectArchive()
    {
        return $this->projectArchiveService;
    }

    public function profile()
    {
        return $this->profileService;
    }

    public function icon()
    {
        return $this->iconService;
    }

    public function userAgent()
    {
        $ide = Ide::get();

        $userAgent = $ide->getName() . ", " . $ide->getVersion() . ", " . $ide->getConfig()->get('app.hash')
            . " (" . System::getProperty('os.name') . ' ' . System::getProperty('os.version') . ")";

        return $userAgent;
    }

    public function shutdown()
    {
        $class = new \ReflectionClass($this);

        foreach ($class->getProperties() as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($this);

            if ($value instanceof AbstractService) {
                unset($this->{$property->getName()});
            }
        }
    }
}