<?php
namespace ide\account\api;


use ide\utils\Json;

class ServiceResponse
{
    protected $json;

    /**
     * ServiceResponse constructor.
     * @param $json
     */
    public function __construct($json)
    {
        $this->json = $json;
    }

    public function data()
    {
        return $this->json['data'];
    }

    public function message()
    {
        return $this->json['message'];
    }

    public function isFail()
    {
        return $this->json['status'] == 'fail';
    }

    public function isSuccess()
    {
        return $this->json['status'] == 'success';
    }

    public function isNotSuccess()
    {
        return !$this->isSuccess();
    }

    public function isError()
    {
        return $this->json['status'] == 'error';
    }

    public function isConnectionFailed()
    {
        return $this->isError() && $this->message() == "ConnectionFailed";
    }

    public function isConnectionRefused()
    {
        return $this->isError() && $this->message() == "ConnectionRefused";
    }

    public function toLog()
    {
        return "{message: {$this->message()}, data = " . \Json::encode($this->data(), false) . "}";
    }
}