<?php
namespace ide\jsplatform\tools;
use ide\Ide;
use ide\tool\AbstractTool;
use php\lang\Process;
use php\lang\System;
use php\lib\arr;
use php\lib\fs;

/**
 * Class NodeTool
 * @package ide\jsplatform\tools
 */
class NodeTool extends AbstractTool
{
    /**
     * @return bool
     */
    public function isAvailable()
    {
        return fs::isFile("{$this->getBinPath()}/node.exe");
    }

    /**
     * @param array $args
     * @param string $workDirectory
     * @return Process
     */
    public function execute(array $args, $workDirectory = null)
    {
        arr::unshift($args, 'node');

        return new Process($args, $workDirectory);
    }

    public function setup()
    {
        return new NodeToolInstaller($this);
    }

    /**
     * @return bool
     */
    public function getName()
    {
        return 'node';
    }
}