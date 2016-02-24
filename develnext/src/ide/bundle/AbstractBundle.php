<?php
namespace ide\bundle;

use ide\project\behaviours\GradleProjectBehaviour;
use ide\project\Project;
use ide\VendorContainer;
use php\util\Configuration;

/**
 * Class AbstractBundle
 * @package ide\bundle
 */
abstract class AbstractBundle
{
    use VendorContainer;

    abstract function getName();
    abstract function getDescription();

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

    public function onSave(Project $project, Configuration $config)
    {
    }

    public function onLoad(Project $project, Configuration $config)
    {
    }
}