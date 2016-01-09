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
}