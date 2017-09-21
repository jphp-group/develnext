<?php
namespace ide\tool;
use facade\Async;
use ide\forms\ToolInstallForm;
use ide\IdeException;
use ide\Logger;
use ide\systems\IdeSystem;
use php\lang\Process;
use php\lib\arr;
use php\lib\reflect;
use php\lib\str;

/**
 * Class IdeToolManager
 * @package ide\tool
 */
class IdeToolManager
{
    /**
     * @var string
     */
    private $toolPath;

    /**
     * @var AbstractTool[]
     */
    private $tools = [];

    /**
     * IdeToolManager constructor.
     * @param $toolPath
     */
    public function __construct($toolPath = null)
    {
        if (!$toolPath) {
            $toolPath = IdeSystem::getFile("tools");
        }

        $this->toolPath = $toolPath;
    }

    public function installer($toolName)
    {
        $tool = $this->get($toolName);

        if ($tool == null) {
            throw new IdeException("Tool '$toolName' is not registered to install()");
        }

        $installer = $tool->setup();

        return $installer;
    }

    public function makeInstall($tool, callable $next, callable $done)
    {
        return function () use ($tool, $next, $done) {
            if ($tool) {
                if ($this->has($tool) && $this->get($tool)->isAvailable()) {
                    $next();
                    return;
                }

                $installer = $this->installer($tool);
                $installer->on('done', function ($success) use ($next, $tool, $done) {
                    if ($success) {
                        $next();
                    } else {
                        $done(false, $tool);
                    }
                }, __CLASS__);

                new ToolInstallForm($installer);
                $installer->run();
            } else {
                $done(true);
            }
        };
    }

    public function install(array $tools, callable $callback)
    {
        /** @var callable $run */
        $run = null;
        $run = function () use (&$tools, &$run, $callback) {
            if (!$tools) {
                $callback(true);
                return;
            }

            $tool = arr::shift($tools);

            if ($tool) {
                if ($this->has($tool) && $this->get($tool)->isAvailable()) {
                    $run();
                    return;
                }

                $installer = $this->installer($tool);
                $doneCallback = function ($success) use (&$run, $tool, $callback) {
                    if ($success) {
                        $run();
                    } else {
                        $callback(false, $tool);
                    }
                };

                if ($installer) {
                    $installer->on('done', $doneCallback, __CLASS__);

                    uiLater(function () use ($installer) {
                        new ToolInstallForm($installer);
                        $installer->run();
                    });
                } else {
                    $doneCallback(true);
                }
            } else {
                $callback(true);
            }
        };

        $run();
    }

    /**
     * @param $toolName
     * @param array $args
     * @param null $workDirectory
     * @return Process
     * @throws IdeException
     */
    public function execute($toolName, array $args = [], $workDirectory = null)
    {
        $tool = $this->get($toolName);

        if (!$tool) {
            throw new IdeException("Tool '$toolName' is not registered to execute()");
        }

        if (!$tool->isAvailable()) {
            throw new IdeException("Tool '$toolName' is not available");
        }

        Logger::info("Execute '$toolName' with args = [" . str::join($args, ', ') . "]");

        return $tool->execute($args, $workDirectory);
    }

    /**
     * @return string
     */
    public function getToolPath()
    {
        return $this->toolPath;
    }

    /**
     * @param AbstractTool $tool
     */
    public function register(AbstractTool $tool)
    {
        $this->tools[$tool->getName()] = $tool;
        $tool->setManager($this);
    }

    /**
     * @param string $name
     * @return AbstractTool
     */
    public function get($name)
    {
        return $this->tools[$name];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->tools[$name]);
    }
}