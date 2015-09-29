<?php
namespace ide\account;

use ide\account\api\AbstractService;
use ide\account\api\AccountService;
use ide\account\api\ProjectService;

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
     * @var ProjectService
     */
    protected $projectService;

    /**
     * ServiceManager constructor.
     */
    public function __construct()
    {
        $this->accountService = new AccountService();
        $this->ideService = new IdeService();
        $this->projectService = new ProjectService();
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

    public function project()
    {
        return $this->projectService;
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