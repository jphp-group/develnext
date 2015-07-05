<?php
namespace ide\project\behaviours;

use ide\misc\GradleBuildConfig;
use ide\project\AbstractProjectBehaviour;

/**
 * Class GradleProjectBehaviour
 * @package ide\project\behaviours
 */
class GradleProjectBehaviour extends AbstractProjectBehaviour
{
    /**
     * @var GradleBuildConfig
     */
    protected $config;

    /**
     * ...
     */
    public function inject()
    {
        $this->config = new GradleBuildConfig($this->project->getRootDir() . "/build.gradle");

        $this->project->on('save', [$this, 'doSave']);
    }

    public function doSave()
    {
        $this->config->save();
    }

    /**
     * @return GradleBuildConfig
     */
    public function getConfig()
    {
        return $this->config;
    }
}