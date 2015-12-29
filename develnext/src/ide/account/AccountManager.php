<?php
namespace ide\account;
use ide\account\api\ServiceResponse;
use ide\commands\account\AccountInfoCommand;
use ide\commands\account\AccountLoginCommand;
use ide\commands\account\AccountLogoutCommand;
use ide\commands\account\AccountRegisterCommand;
use ide\forms\LoginForm;
use ide\forms\UpdateAvailableForm;
use ide\Ide;
use ide\Logger;
use ide\misc\AbstractCommand;
use ide\misc\EventHandlerBehaviour;
use ide\ui\Notifications;
use ide\utils\Json;
use php\gui\UXApplication;
use php\gui\UXDialog;
use php\gui\UXTrayNotification;

/**
 * Class AccountManager
 * @package ide\account
 */
class AccountManager
{
    use EventHandlerBehaviour;

    /**
     * @var LoginForm
     */
    protected $loginForm;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var mixed
     */
    protected $accountData;

    /**
     * @var AbstractCommand[]
     */
    protected $accountIdeCommands;

    protected $accountIdeCommandsForAuthorized;

    /**
     * AccountManager constructor.
     */
    public function __construct()
    {
        $this->loginForm = new LoginForm();
        $this->accessToken = Ide::get()->getUserConfigValue('account.accessToken');
    }

    /**
     * @param $token
     */
    public function setAccessToken($token)
    {
        if (!$this->accessToken && $token) {
            UXApplication::runLater(function () {
                $this->trigger('login');
            });
        }

        if (!$token && $this->accessToken) {
            UXApplication::runLater(function () {
                $this->trigger('logout');
            });
        }

        Ide::get()->setUserConfigValue('account.accessToken', $token);
        $this->accessToken = $token;

        $this->updateAccount();
    }

    /**
     * ...
     */
    public function updateAccount()
    {
        if ($this->isAuthorized()) {
            Ide::service()->account()->getAsync(function (ServiceResponse $response) {
                if ($response->isSuccess()) {
                    if (!$this->accountData) {
                        Notifications::showAccountAuthWelcome($response->data());
                    }

                    $this->accountData = $response->data();

                    $response = Ide::service()->ide()->getLastUpdate('NIGHT');
                    $hash = Ide::get()->getConfig()->get('app.hash');

                    if ($response->isSuccess()) {
                        $rHash = $response->data()['hash'];

                        if ($hash < $rHash) {
                            UXApplication::runLater(function () use ($response) {

                                $dialog = new UpdateAvailableForm();
                                $dialog->tryShow($response->data());
                            });
                        }
                    } else {
                        Logger::warn("Unable get last updates, message = {$response->message()}");
                    }

                    UXApplication::runLater(function () {
                        $this->trigger('update', [$this->accountData]);
                    });
                }
            });

        } else {
            UXApplication::runLater(function () {
                $this->trigger('update', [null]);
            });

            $this->accountData = [];
        }
    }

    public function updateIdeUi()
    {
        $logged = Ide::get()->getInternalList('.dn/account/loggedCommands');
        $unlogged = Ide::get()->getInternalList('.dn/account/unloggedCommands');

        UXApplication::runLater(function () use ($logged, $unlogged) {
            $ide = Ide::get();

            if ($this->isAuthorized()) {
                foreach ($unlogged as $class) {
                    $ide->unregisterCommand($class);
                }

                foreach ($logged as $class) {
                    $ide->registerCommand(new $class());
                }
            } else {
                foreach ($logged as $class) {
                    $ide->unregisterCommand($class);
                }

                foreach ($unlogged as $class) {
                    $ide->registerCommand(new $class());
                }
            }
        });
    }

    /**
     * @return array [email, name, avatar, createdAt, updatedAt]
     */
    public function getAccountData()
    {
        return $this->accountData;
    }

    public function getAccountEmail()
    {
        return $this->accountData['email'];
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function isAuthorized()
    {
        return !!$this->accessToken;
    }

    public function authorize($always = false)
    {
        //$this->updateIdeUi();

        if (!$this->accessToken || $always) {
            if (!$this->loginForm->visible) {
                $this->loginForm->show();
                return true;
            } else {
                return false;
            }
        }

        $this->updateAccount();

        return true;
    }
}