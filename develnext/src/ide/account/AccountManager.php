<?php
namespace ide\account;
use ide\forms\LoginForm;
use ide\Ide;

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
     * AccountManager constructor.
     */
    public function __construct()
    {
        $this->loginForm = new LoginForm();
        $this->accessToken = Ide::get()->getUserConfigValue('account.accessToken');
    }

    public function authorize()
    {
        if (!$this->accessToken) {
            $this->loginForm->show();
        }
    }
}