<?php
namespace php\gui\framework;


trait ApplicationTrait
{
    public function factory($name)
    {
        return app()->factory($name);
    }

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