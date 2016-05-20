<?php
namespace ide\bundle;

use ide\Logger;
use ide\project\behaviours\GradleProjectBehaviour;
use ide\project\Project;
use ide\VendorContainer;
use php\io\IOException;
use php\io\Stream;
use php\lib\reflect;
use php\lib\str;
use php\util\Configuration;
use php\util\Scanner;

/**
 * Class AbstractBundle
 * @package ide\bundle
 */
abstract class AbstractBundle
{
    use VendorContainer;

    /**
     * @var string
     */
    protected $bundleDirectory = null;

    abstract function getName();
    abstract function getDescription();

    /**
     * @param Project $project
     * @return bool
     */
    public function isAvailable(Project $project)
    {
        return true;
    }

    public function getIcon()
    {
        return null;
    }

    public function getVersion()
    {
        return null;
    }

    /**
     * @return array
     */
    public function getUseImports()
    {
        return [];
    }

    /**
     * @return string[] classes
     */
    public function getDependencies()
    {
        return [];
    }

    /**
     * @param Project $project
     * @param string $env
     * @param callable|null $log
     */
    public function onPreCompile(Project $project, $env, callable $log = null)
    {
    }

    /**
     * @param GradleProjectBehaviour $gradle
     */
    public function applyForGradle(GradleProjectBehaviour $gradle)
    {
    }

    /**
     * On register in ide globally.
     */
    public function onRegister()
    {
    }

    /**
     * @param Project $project
     */
    public function onAdd(Project $project)
    {
        $this->deleteVendorDirectory();
        $this->copyVendorDirectory();
    }

    /**
     * @param Project $project
     */
    public function onRemove(Project $project)
    {
        $this->deleteVendorDirectory();
    }

    public function onSave(Project $project, Configuration $config)
    {
    }

    public function onLoad(Project $project, Configuration $config)
    {
    }

    /**
     * @return string
     */
    public function getBundleDirectory()
    {
        return $this->bundleDirectory;
    }

    /**
     * @param string $bundleDirectory
     */
    public function setBundleDirectory($bundleDirectory)
    {
        $this->bundleDirectory = $bundleDirectory;
    }
}