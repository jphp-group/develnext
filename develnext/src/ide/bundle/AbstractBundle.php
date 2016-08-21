<?php
namespace ide\bundle;

use ide\library\IdeLibraryBundleResource;
use ide\Logger;
use ide\project\behaviours\GradleProjectBehaviour;
use ide\project\Project;
use ide\project\ProjectModule;
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

    /**
     * @deprecated
     */
    function getName() {}

    /**
     * @deprecated
     */
    function getDescription() {}

    /**
     * @return array
     */
    public function useNewBundles()
    {
        return [];
    }

    /**
     * @param Project $project
     */
    public function addToInspector(Project $project)
    {
    }

    /**
     * @param Project $project
     */
    public function removeFromInspector(Project $project)
    {
    }

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
     * @return ProjectModule[]
     */
    public function getProjectModules()
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
     * On register in ide globally.
     * @param IdeLibraryBundleResource $resource
     */
    public function onRegister(IdeLibraryBundleResource $resource)
    {
    }

    /**
     * @param Project $project
     * @param AbstractBundle $owner
     */
    public function onAdd(Project $project, AbstractBundle $owner = null)
    {
        foreach ($this->getProjectModules() as $module) {
            $project->addModule($module, reflect::typeOf($this));
        }

        $this->deleteVendorDirectory();
        $this->copyVendorDirectory();

        $project->loadDirectoryForInspector($this->getProjectVendorDirectory());
    }

    /**
     * @param Project $project
     * @param AbstractBundle $owner
     */
    public function onRemove(Project $project, AbstractBundle $owner = null)
    {
        foreach ($this->getProjectModules() as $module) {
            $project->removeModule($module, reflect::typeOf($this));
        }

        $this->deleteVendorDirectory();
        $project->unloadDirectoryForInspector($this->getProjectVendorDirectory());
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