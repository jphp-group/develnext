<?php
namespace ide\jsplatform\tools;

use ide\tool\AbstractTool;
use ide\tool\AbstractToolInstaller;
use php\lang\Process;
use php\lib\arr;
use php\lib\fs;

class HttpServerTool extends AbstractTool
{
    /**
     * @param array $args
     * @param $workDirectory
     * @return Process
     */
    public function execute(array $args, $workDirectory = null)
    {
        arr::unshift($args, $this->getBinPath() . '/http-server');

        return $this->manager->execute('node', $args);
    }

    /**
     * @return bool
     */
    public function getName()
    {
        return 'http-server';
    }

    /**
     * @return string
     */
    public function getBinPath()
    {
        if ($this->manager->has('npm')) {
            return $this->manager->get('npm')->getBinPath() . '/node_modules/http-server/bin';
        }

        return parent::getBinPath();
    }

    /**
     * @return bool
     */
    public function isAvailable()
    {
        $npmTool = $this->manager->get('npm');

        return $npmTool->isAvailable() && fs::isDir($npmTool->getBinPath() . '/node_modules/http-server');
    }

    /**
     * @return AbstractToolInstaller
     */
    public function setup()
    {
        return new AnyNpmToolInstaller($this);
    }
}