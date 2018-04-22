<?php
namespace ide\project;

/**
 * Class AbstractProjectSupport
 * @package ide\project
 */
abstract class AbstractProjectSupport
{
    /**
     * @param Project $project
     * @return mixed
     */
    abstract public function isFit(Project $project);

    /**
     * @param Project $project
     * @return mixed
     */
    abstract public function onLink(Project $project);

    /**
     * @param Project $project
     * @return mixed
     */
    abstract public function onUnlink(Project $project);

    /**
     *
     */
    public function onRegisterInIDE()
    {
        // nop.
    }
}