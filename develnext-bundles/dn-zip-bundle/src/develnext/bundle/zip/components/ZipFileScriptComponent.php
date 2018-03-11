<?php
namespace develnext\bundle\zip\components;

use bundle\zip\ZipFileScript;
use ide\scripts\AbstractScriptComponent;

class ZipFileScriptComponent extends AbstractScriptComponent
{
    public function isOrigin($any)
    {
        return $any instanceof ZipFileScriptComponent;
    }

    public function getIcon()
    {
        return 'develnext/bundle/zip/zipFile16.png';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Zip файл';
    }

    public function getIdPattern()
    {
        return "zipFile%s";
    }

    /**
     * @return string
     */
    public function getType()
    {
        return ZipFileScript::class;
    }

    public function getDescription()
    {
    }
}