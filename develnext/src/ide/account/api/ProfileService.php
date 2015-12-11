<?php
namespace ide\account\api;

/**
 * Class ProfileService
 * @package ide\account\api
 *
 * @method updateNameAsync($newName, callable $callback)
 * @method updateAvatarAsync($mediaUid, callable $callback)
 * @method updatePasswordAsync($oldPassword, $newPassword, callable $callback)
 */
class ProfileService extends AbstractService
{
    /**
     * @param $newName
     * @return ServiceResponse
     */
    public function updateName($newName)
    {
        return $this->execute('profile/update-name', ['name' => $newName]);
    }

    /**
     * @param $mediaUid
     * @return ServiceResponse
     * @throws ServiceException
     * @throws ServiceInvalidResponseException
     * @throws ServiceNotAvailableException
     */
    public function updateAvatar($mediaUid)
    {
        return $this->execute('profile/update-avatar', ['mediaUid' => $mediaUid]);
    }

    /**
     * @param $oldPassword
     * @param $newPassword
     * @return ServiceResponse
     */
    public function updatePassword($oldPassword, $newPassword)
    {
        return $this->execute('profile/update-password', ['oldPassword' => $oldPassword, 'newPassword' => $newPassword]);
    }
}