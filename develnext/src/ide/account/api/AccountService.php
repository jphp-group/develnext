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
 * @method ServiceResponse changeLoginAsync($login, $callback = null)
 * @method ServiceResponse changePasswordAsync($oldPassword, $newPassword, $callback = null)
 * @method ServiceResponse changeAvatarAsync($fileId, $callback = null)
 * @method ServiceResponse deleteAvatarAsync($callback = null)
 * @method ServiceResponse registerAsync($email, $name, $password, $captchaKey, $captchaWord, $callback)
 * @method ServiceResponse confirmAsync($email, $confirmKey, $callback)
 * @method ServiceResponse getAsync($callback)
 * @method ServiceResponse logoutAsync($callback = null)
 * @method ServiceResponse restorePasswordAsync($email, callable $callback = null)
 * @method ServiceResponse restorePasswordConfirmAsync($email, $confirmKey, callable $callback = null)
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
     * @return array [string, UXImage]
     */
    public function captcha()
    {
        $conn = $this->getConnection('auth/captcha');

        if ($conn) {
            $conn->setRequestProperty('Content-Type', 'text/html');
            $key = $conn->getHeaderField('X-Captcha-Key');

            $stream = $conn->getInputStream();

            if (!$stream || !$key) {
                return null;
            }

            return [$key, new UXImage($stream)];
        }

        return null;
    }

    /**
     * @return ServiceResponse
     * @throws ServiceException
     * @throws ServiceInvalidResponseException
     * @throws ServiceNotAvailableException
     */
    public function get()
    {
        return $this->executeGet('auth/account');
    }

    /**
     * @param string $newLogin
     * @return ServiceResponse
     */
    public function changeLogin($newLogin)
    {
        return $this->execute('auth/account/login', ['login' => $newLogin], 'PUT');
    }

    /**
     * @param string $oldPassword
     * @param string $newPassword
     * @return ServiceResponse
     */
    public function changePassword($oldPassword, $newPassword)
    {
        return $this->execute('auth/account/password', ['oldPassword' => $oldPassword, 'newPassword' => $newPassword], 'PUT');
    }

    /**
     * @param string $fileId
     *
     * @return ServiceResponse
     */
    public function changeAvatar($fileId)
    {
        return $this->execute('auth/account/avatar', ['fileId' => $fileId], 'PUT');
    }

    /**
     * @return ServiceResponse
     */
    public function deleteAvatar()
    {
        return $this->execute('auth/account/avatar', [], 'DELETE');
    }

    /**
     * @param $email
     * @param $confirmKey
     * @return ServiceResponse
     */
    public function confirm($email, $confirmKey)
    {
        return $this->execute('auth/confirm', [
            'login' => $email,
            'code' => $confirmKey
        ]);
    }

    /**
     * @param $email
     * @return ServiceResponse
     */
    public function restorePassword($email)
    {
        return $this->execute('auth/restore-password', [
            'login' => $email
        ]);
    }

    /**
     * @param $email
     * @param $confirmKey
     * @return ServiceResponse
     */
    public function restorePasswordConfirm($email, $confirmKey)
    {
        return $this->execute('auth/restore-password-confirm', [
            'login' => $email,
            'code' => $confirmKey
        ]);
    }

    /**
     * @param $email
     * @param $password
     * @param $captchaKey
     * @param $captchaWord
     * @return ServiceResponse
     * @throws ServiceException
     * @throws ServiceInvalidResponseException
     * @throws ServiceNotAvailableException
     */
    public function register($email, $name, $password, $captchaKey, $captchaWord)
    {
        return $this->execute('auth/register', [
            'email' => $email,
            'login' => $name,
            'password' => $password,
            'captchaKey' => $captchaKey,
            'captchaText' => $captchaWord
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
        return $this->execute('auth/login', [
            'login' => $email,
            'password' => $password
        ]);
    }

    /**
     * @return ServiceResponse
     */
    public function logout()
    {
        return $this->executeGet('auth/logout');
    }
}