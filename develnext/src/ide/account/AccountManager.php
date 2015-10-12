<?php
namespace ide\account;
use ide\account\api\ServiceResponse;
use ide\commands\account\AccountInfoCommand;
use ide\commands\account\AccountLoginCommand;
use ide\commands\account\AccountLogoutCommand;
use ide\commands\account\AccountRegisterCommand;
use ide\forms\LoginForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use php\gui\UXApplication;

/**
 * Class AccountManager
 * @package ide\account
 */
class AccountManager
{
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
                        Ide::get()->getMainForm()->toast("Добро пожаловать, вы вошли через {$response->data()['email']}");
                    }

                    $this->accountData = $response->data();

                    $this->updateIdeUi();
                }
            });
        } else {
            $this->accountData = [];
            $this->updateIdeUi();
        }
    }

    public function updateIdeUi()
    {
        return;

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
        $this->updateIdeUi();

        if (!$this->accessToken) {
            if (!$always) {
                $ideHits = (int) Ide::get()->getUserConfigValue('account.ideHits', 0);

                Ide::get()->setUserConfigValue('account.ideHits', $ideHits + 1);

                /*if ($ideHits % 10 != 0) {
                    return;
                }  */
            }

             //$this->loginForm->showAndWait();
        }

        //Ide::service()->ide()->startAsync(null);
        //$this->updateAccount();
    }
}