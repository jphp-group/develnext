<?php
namespace ide\account\api;

/**
 * Class ChatService
 * @package ide\account\api
 *
 * @method getPrivateRoomAsync($accountId, callable $callback)
 * @method roomLastUpdatedAsync($roomId, callable $callback)
 * @method getMessagesAsync($roomId, $page, callable $callback)
 * @method sendMessageAsync($roomId, $message, callable $callback)
 */
class ChatService extends AbstractService
{
    /**
     * @param $accountId
     * @return ServiceResponse
     * @throws ServiceException
     * @throws ServiceInvalidResponseException
     * @throws ServiceNotAvailableException
     */
    public function getPrivateRoom($accountId)
    {
        return $this->execute('chat/get-private-room', ['accountId' => $accountId]);
    }

    /**
     * @param $roomId
     * @return ServiceResponse
     * @throws ServiceException
     * @throws ServiceInvalidResponseException
     * @throws ServiceNotAvailableException
     */
    public function roomLastUpdated($roomId)
    {
        return $this->execute('chat/room-last-updated', ['roomId' => $roomId]);
    }

    /**
     * @param $roomId
     * @param int $page
     * @return ServiceResponse
     */
    public function getMessages($roomId, $page = 0)
    {
        return $this->execute('chat/get-messages', ['roomId' => $roomId, 'page' => $page]);
    }

    /**
     * @param $roomId
     * @param $message
     * @return ServiceResponse
     * @throws ServiceException
     * @throws ServiceInvalidResponseException
     * @throws ServiceNotAvailableException
     */
    public function sendMessage($roomId, $message)
    {
        return $this->execute('chat/send-message', ['roomId' => $roomId, 'text' => $message]);
    }
}