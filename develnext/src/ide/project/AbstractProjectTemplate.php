<?php
namespace ide\project;

abstract class AbstractProjectTemplate
{
    abstract public function getName();
    abstract public function getDescription();

    /**
     * desktop, web, etc.
     * @return string
     */
    abstract public function getSupportContext(): string;

    public function getIcon()
    {
        return $this->getIcon32();
    }

    abstract public function getIcon32();

    /**
     * @param Project $project
     *
     * @return Project
     */
    abstract public function makeProject(Project $project);

    /**
     * @param Project $project
     * @return mixed
     */
    abstract public function recoveryProject(Project $project);

    /**
     * @param Project $project
     * @return bool
     */
    public function isProjectWillMigrate(Project $project)
    {
        return false;
    }
}