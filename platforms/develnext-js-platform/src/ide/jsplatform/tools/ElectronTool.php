<?php
namespace ide\jsplatform\tools;

use ide\tool\AbstractTool;
use ide\tool\AbstractToolInstaller;
use php\lang\Process;
use php\lib\arr;
use php\lib\fs;

class ElectronTool extends AbstractTool
{
    /**
     * @param array $args
     * @param $workDirectory
     * @return Process
     */
    public function execute(array $args, $workDirectory = null)
    {
        arr::unshift($args, $this->getBinPath() . '/electron');

        return new Process($args, $workDirectory);
    }

    /**
     * @return bool
     */
    public function getName()
    {
        return 'electron';
    }

    /**
     * @return string
     */
    public function getBinPath()
    {
        if ($this->manager->has('npm')) {
            return $this->manager->get('npm')->getBinPath() . '/node_modules/electron/dist';
        }

        return parent::getBinPath();
    }

    /**
     * @return bool
     */
    public function isAvailable()
    {
        $npmTool = $this->manager->get('npm');

        return $npmTool->isAvailable() && fs::isDir($npmTool->getBinPath() . '/node_modules/electron');
    }

    /**
     * @return AbstractToolInstaller
     */
    public function setup()
    {
        return new AnyNpmToolInstaller($this);
    }
}