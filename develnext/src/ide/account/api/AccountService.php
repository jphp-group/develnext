<?php
namespace ide\account\api;


use ide\Ide;
use php\gui\UXApplication;
use php\gui\UXImage;
use php\lang\System;

/**
 * Class AccountService
 * @package ide\account\api
 *
 * @method ServiceResponse authAsync($email, $password, $callback)
 * @method ServiceResponse authExternalAsync($confirmKey, $callback)
 * @method ServiceResponse authVkAsync($callback)
 * @method ServiceResponse registerAsync($email, $password, $captchaWord, $callback)
 * @method ServiceResponse confirmAsync($email, $confirmKey, $callback)
 * @method ServiceResponse getAsync($callback)
 * @method ServiceResponse logoutAsync($callback)
 */
class AccountService extends AbstractService
{
    protected function getOsName()
    {
        $osName = System::getProperty('os.name');

        return $osName ?: 'unknown';
    }

    protected function getOsUser()
    {
        $osUser = System::getProperty('os.user');

        return $osUser ?: 'unknown';
    }

    protected function getDeviceId()
    {
        $macAddress = UXApplication::getMacAddress();

        return ($macAddress ?: "unknown") . " (" . Ide::get()->getName() . "/" . Ide::get()->getVersion() . ")";
    }

    /**
     * @return UXImage
     */
    public function captcha()
    {
        return UXImage::ofUrl($this->makeUrl('account/captcha'), true);
    }

    /**
     * @return ServiceResponse
     * @throws ServiceException
     * @throws ServiceInvalidResponseException
     * @throws ServiceNotAvailableException
     */
    public function get()
    {
        return $this->execute('account/get', []);
    }

    /**
     * @param $email
     * @param $confirmKey
     * @return ServiceResponse
     */
    public function confirm($email, $confirmKey)
    {
        return $this->execute('account/confirm', [
            'email' => $email,
            'confirmKey' => $confirmKey,

            'deviceId' => $this->getDeviceId(),
            'osName' => $this->getOsName(),
            'osUser' => $this->getOsUser(),
        ]);
    }

    /**
     * @param $email
     * @param $password
     * @param $captchaWord
     * @return ServiceResponse
     * @throws ServiceException
     * @throws ServiceInvalidResponseException
     * @throws ServiceNotAvailableException
     */
    public function register($email, $password, $captchaWord)
    {
        return $this->execute('account/register', [
            'email' => $email,
            'password' => $password,
            'captchaWord' => $captchaWord
        ]);
    }

    /**
     * @param $email
     * @param $password
     * @return ServiceResponse
     * @throws ServiceException
     * @throws ServiceInvalidResponseException
     * @throws ServiceNotAvailableException
     */
    public function auth($email, $password)
    {
        return $this->execute('account/auth', [
            'email' => $email,
            'password' => $password,

            'deviceId' => $this->getDeviceId(),
            'osName' => $this->getOsName(),
            'osUser' => $this->getOsUser(),
        ]);
    }

    public function authExternal($confirmKey)
    {
        return $this->execute('account/auth-external', [
            'confirmKey' => $confirmKey,

            'deviceId' => $this->getDeviceId(),
            'osName' => $this->getOsName(),
            'osUser' => $this->getOsUser(),
        ]);
    }

    public function authVk()
    {
        return $this->execute('account/auth/vk', []);
    }

    public function logout()
    {
        return $this->execute('account/logout', []);
    }
}