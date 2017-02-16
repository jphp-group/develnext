<?php
namespace ide;

use php\lib\reflect;
use php\lib\str;

trait IdeConfigurable
{
    /**
     * @return IdeConfiguration
     */
    protected function ideConfig()
    {
        $name = str::replace(get_class($this), "\\", "/");

        $config = Ide::get()->getUserConfig("config/$name");
        $config->setAutoSave(true);

        return $config;
    }
}