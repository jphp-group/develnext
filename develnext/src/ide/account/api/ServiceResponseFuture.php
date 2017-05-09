<?php
namespace ide\account\api;


use ide\misc\EventHandlerBehaviour;
use php\lib\str;

class ServiceResponseFuture extends ServiceResponse
{
    use EventHandlerBehaviour;

    public $__used = false;

    protected $triggered = false;

    /**
     * @param ServiceResponse $response
     */
    public function apply(ServiceResponse $response)
    {
        $this->json = $response->json;
        $this->code = (int) $response->code;
    }

    public function __invoke(ServiceResponse $response)
    {
        if ($this->__used) {
            $this->__used = false;
            $this->triggered = true;

            $this->trigger('action', [$response]);

            if ($this->isSuccess()) {
                $this->trigger('success', [$response]);
            }

            if ($this->isNotSuccess()) {
                $this->trigger('fail', [$response]);
            }
        }
    }
}