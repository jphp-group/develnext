<?php
namespace ide\account;

use ide\account\api\AbstractService;
use ide\account\api\AccountService;

/**
 * Class ServiceManager
 * @package ide\account
 */
class ServiceManager
{
    /**
     * @var AccountService
     */
    protected $accountService;

    /**
     * @var IdeService
     */
    protected $ideService;

    /**
     * ServiceManager constructor.
     */
    public function __construct()
    {
        $this->accountService = new AccountService();
        $this->ideService = new IdeService();
    }

    /**
     * @return AccountService
     */
    public function account()
    {
        return $this->accountService;
    }

    /**
     * @return IdeService
     */
    public function ide()
    {
        return $this->ideService;
    }

    public function shutdown()
    {
        $class = new \ReflectionClass($this);

        foreach ($class->getProperties() as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($this);

            if ($value instanceof AbstractService) {
                unset($this->{$property->getName()});
            }
        }
    }
}