<?php
namespace php\gui\framework;


trait ApplicationTrait
{
    /**
     * @param string $name
     * @return AbstractFactory
     * @throws \php\lang\IllegalArgumentException
     */
    public function factory($name)
    {
        return app()->factory($name);
    }

    /**
     * @return null|AbstractModule
     * @return-dynamic app\modules\AppModule
     */
    public function appModule()
    {
        return app()->appModule();
    }

    /**
     * @param string $name
     * @return AbstractForm
     * @return-dynamic app\forms\$0
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