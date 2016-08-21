<?php
namespace bundle\http;

/**
 * Class HttpAsyncResponse
 * @package bundle\http
 */
class HttpAsyncResponse
{
    protected $onSuccess;
    protected $onError;
    protected $onDone;

    /**
     * @param callable $callback
     * @return HttpAsyncResponse
     */
    function whenDone(callable $callback)
    {
        $this->onDone = $callback;
        return $this;
    }

    /**
     * @param callable $callback
     * @return HttpAsyncResponse
     */
    function whenSuccess(callable $callback)
    {
        $this->onSuccess = $callback;
        return $this;
    }

    /**
     * @param callable $callback
     * @return HttpAsyncResponse
     */
    function whenError(callable $callback)
    {
        $this->onError = $callback;
        return $this;
    }

    /**
     * @param HttpResponse $response
     */
    public function trigger(HttpResponse $response)
    {
        if ($this->onSuccess && $response->isSuccess()) call_user_func($this->onSuccess, $response);
        if ($this->onError && $response->isFail()) call_user_func($this->onError, $response);

        if ($this->onDone) call_user_func($this->onDone, $response);
    }
}