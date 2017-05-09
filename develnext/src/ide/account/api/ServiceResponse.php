<?php
namespace ide\account\api;

use facade\Json;

class ServiceResponse
{
    protected $json;
    protected $code;

    /**
     * ServiceResponse constructor.
     * @param $code
     * @param $json
     */
    public function __construct($code, $json)
    {
        $this->json = $json;
        $this->code = (int) $code;
    }

    public function result($field = null)
    {
        if ($field) {
            return $this->json[$field];
        } else {
            return $this->json;
        }
    }

    public function data()
    {
        return $this->result('data');
    }

    public function message()
    {
        return $this->result('message');
    }

    public function isFail()
    {
        return $this->code >= 400 && $this->code < 500;
    }

    public function isNotFound()
    {
        return $this->code === 404;
    }

    public function isBadRequest()
    {
        return $this->code === 400;
    }

    public function isAccessDenied()
    {
        return $this->code === 403;
    }

    public function isInvalidValidation()
    {
        return $this->code === 422;
    }

    public function isConflict()
    {
        return $this->code === 409;
    }

    public function isSuccess()
    {
        return $this->code === 200;
    }

    public function isNotSuccess()
    {
        return !$this->isSuccess();
    }

    public function isError()
    {
        return $this->code >= 500;
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
        return "{message: {$this->message()}, result = " . Json::encode($this->result(), false) . "}";
    }
}