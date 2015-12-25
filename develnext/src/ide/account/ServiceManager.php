<?php
namespace ide\account;

use ide\account\api\AbstractService;
use ide\account\api\AccountService;
use ide\account\api\MediaService;
use ide\account\api\NoticeService;
use ide\account\api\ProfileService;
use ide\account\api\ProjectService;
use ide\account\api\ServiceResponse;
use ide\Logger;
use ide\misc\EventHandlerBehaviour;
use ide\ui\Notifications;
use ide\utils\Json;
use php\lang\IllegalArgumentException;
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
    protected $endpoint = 'http://develnext.ru';

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
     * @var NoticeService
     */
    protected $noticeService;

    /**
     * @var MediaService
     */
    protected $mediaService;

    /**
     * ServiceManager constructor.
     */
    public function __construct()
    {
        $this->accountService = new AccountService();
        $this->profileService = new ProfileService();
        $this->ideService = new IdeService();
        $this->projectService = new ProjectService();
        $this->noticeService = new NoticeService();
        $this->mediaService = new MediaService();

        $this->accountService->on('exception', function () { $this->updateStatus(); });
        $this->projectService->on('exception', function () { $this->updateStatus(); });
        $this->ideService->on('exception', function ($methodName) {
            if ($methodName != 'ide/status') {
                $this->updateStatus();
            }
        });

        $timer = new TimerScript(20 * 1000, true, [$this, 'updateStatus']);
        $timer->start();

        $this->on('notice', [$this, 'updateNotices']);
    }

    protected function changeStatus($status)
    {
        if ($status['endpoint']) {
            $this->endpoint = $status['endpoint'];
        }

        if ($status['account']) {
            $noticeUpdatedAt = $status['account']['noticeUpdatedAt'];

            if ($noticeUpdatedAt != $this->noticeService->getLastUpdatedAt()) {
                $this->noticeService->setLastUpdatedAt($noticeUpdatedAt);
                $this->trigger('notice');
            }
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
        return $this->endpoint;
    }

    public function updateNotices()
    {
        if ($this->ideService) {
            $this->ideService->noticesAsync(function (ServiceResponse $response) {
                if ($response->isSuccess()) {
                    $list = $response->data();

                    foreach ($list as $notice) {
                        try {
                            Notifications::show($notice['title'], $notice['message'], $notice['type']);
                        } catch (IllegalArgumentException $e) {
                            Notifications::show($notice['title'], $notice['message']);
                        }
                    }
                }
            });
        }
    }

    public function updateStatus()
    {
        if ($this->ideService) {
            Logger::info("Update status ...");

            $this->ideService->statusAsync(function (ServiceResponse $response) {
                Logger::info("Update status response = {message: {$response->message()}, data: " . Json::encode($response->data()) . "}");

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
                        $status = $response->data();
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
     * @return MediaService
     */
    public function media()
    {
        return $this->mediaService;
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

    public function profile()
    {
        return $this->profileService;
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