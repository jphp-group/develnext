<?php
namespace ide;

abstract class AbstractExtension
{
    abstract public function onRegister();
    abstract public function onIdeStart();
    abstract public function onIdeShutdown();

    /**
     * @return string[] classes of AbstractExtension or AbstractBundle
     */
    public function getDependencies()
    {
        return [];
    }
}