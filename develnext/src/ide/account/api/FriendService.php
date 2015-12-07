<?php
namespace ide\account\api;


/**
 * Class FriendService
 * @package ide\account\api
 *
 * @method getListAsync(callable $callback)
 * @method addAsync($accountId, $message, callable $callback)
 * @method removeAsync($accountId, $message, callable $callback)
 */
class FriendService extends AbstractService
{
    /**
     * @return ServiceResponse
     * @throws ServiceException
     * @throws ServiceInvalidResponseException
     * @throws ServiceNotAvailableException
     * @return ServiceResponse
     */
    public function getList()
    {
        return $this->execute('friend/list', []);
    }

    /**
     * @param $accountId
     * @param null $message
     * @return ServiceResponse
     * @throws ServiceException
     * @throws ServiceInvalidResponseException
     * @throws ServiceNotAvailableException
     */
    public function add($accountId, $message = null)
    {
        return $this->execute('friend/add', [
            'accountId' => $accountId,
            'message' => $message,
        ]);
    }

    /**
     * @param $accountId
     * @param null $message
     * @return ServiceResponse
     * @throws ServiceException
     * @throws ServiceInvalidResponseException
     * @throws ServiceNotAvailableException
     * @return ServiceResponse
     */
    public function remove($accountId, $message = null)
    {
        return $this->execute('friend/remove', [
            'accountId' => $accountId,
            'message'   => $message,
        ]);
    }
}