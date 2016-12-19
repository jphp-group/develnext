<?php
namespace ide\jsplatform\tools;
use ide\Ide;
use ide\Logger;
use ide\systems\IdeSystem;
use ide\tool\AbstractTool;
use ide\tool\AbstractToolInstaller;
use php\lang\Process;
use php\lang\System;
use php\lib\arr;
use php\lib\fs;
use php\lib\num;

/**
 * Class NpmTool
 * @package ide\jsplatform\tools
 */
class NpmTool extends AbstractTool
{
    /**
     * @param array $args
     * @param string $workDirectory
     * @return Process
     */
    public function execute(array $args, $workDirectory = null)
    {
        $command = $this->getBinPath() . "/npm";

        if (Ide::get()->isWindows()) {
            $command = "$command.cmd";

            arr::unshift($args, 'cmd', '/c', $command);
        } else {
            arr::unshift($args, $command);
        }

        return new Process($args, $workDirectory);
    }

    /**
     * @return string
     */
    public function getBinPath()
    {
        return $this->manager->get('node')->getBinPath();
    }

    /**
     * @return bool
     */
    public function getName()
    {
        return 'npm';
    }

    /**
     * @return bool
     */
    public function isAvailable()
    {
        return fs::isFile("{$this->getBinPath()}/npm");
    }

    /**
     * @return AbstractToolInstaller
     */
    public function setup()
    {
        return null;
    }
}