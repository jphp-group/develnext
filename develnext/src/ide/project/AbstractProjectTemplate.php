<?php
namespace ide\project;

abstract class AbstractProjectTemplate
{
    abstract public function getName();
    abstract public function getDescription();

    abstract public function getIcon();
    abstract public function getIcon32();

    /**
     * @param Project $project
     *
     * @return Project
     */
    abstract public function makeProject(Project $project);
}