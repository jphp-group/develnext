<?php
namespace develnext\bundle\orientdb;

use ide\bundle\AbstractBundle;
use ide\project\Project;
use php\orientdb\ODatabase;
use php\orientdb\ODocument;

class OrientDbBundle extends AbstractBundle
{
    function getName()
    {
        return "Orient DB";
    }

    function getDescription()
    {
        return "Embedded NoSQL database";
    }

    public function getDependencies()
    {
        return [
            JPHPOrientDbBundle::class
        ];
    }

    public function getUseImports()
    {
        return [ODocument::class, ODatabase::class];
    }

    public function onPreCompile(Project $project, $env, callable $log = null)
    {
    }
}