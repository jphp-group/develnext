<?php
namespace php\gui\framework;


trait ApplicationTrait
{
    /**
     * @param $name
     * @return AbstractFactory
     * @throws \php\lang\IllegalArgumentException
     */
    public function factory($name)
    {
        return app()->factory($name);
    }

    /**
     * @return null|AbstractModule
     */
    public function appModule()
    {
        return app()->appModule();
    }

    /**
     * @param $name
     * @return AbstractForm
     */
    public function form($name)
    {
        return app()->getForm($name);
    }

    /**
     * @param $name
     * @return AbstractForm
     */
    public function originForm($name)
    {
        return app()->getOriginForm($name);
    }
}