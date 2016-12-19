<?php
namespace ide\jsplatform\tools;

use ide\tool\AbstractTool;
use ide\tool\AbstractToolInstaller;
use php\lang\Process;
use php\lib\arr;
use php\lib\fs;

class GulpTool extends AbstractTool
{
    /**
     * @param array $args
     * @param $workDirectory
     * @return Process
     */
    public function execute(array $args, $workDirectory = null)
    {
        arr::unshift($args, $this->getBinPath() . '/gulp.js');

        $this->manager->execute('node', $args);
    }

    /**
     * @return bool
     */
    public function getName()
    {
        return 'gulp';
    }

    /**
     * @return string
     */
    public function getBinPath()
    {
        if ($this->manager->has('npm')) {
            return $this->manager->get('npm')->getBinPath() . '/node_modules/gulp/bin';
        }

        return parent::getBinPath();
    }

    /**
     * @return bool
     */
    public function isAvailable()
    {
        $npmTool = $this->manager->get('npm');

        return $npmTool->isAvailable() && fs::isDir($npmTool->getBinPath() . '/node_modules/gulp');
    }

    /**
     * @return AbstractToolInstaller
     */
    public function setup()
    {
        return new AnyNpmToolInstaller($this);
    }
}