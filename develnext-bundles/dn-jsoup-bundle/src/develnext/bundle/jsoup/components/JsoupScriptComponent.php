<?php
namespace develnext\bundle\jsoup\components;

use ide\scripts\AbstractScriptComponent;
use script\JsoupScript;

class JsoupScriptComponent extends AbstractScriptComponent
{
    public function isOrigin($any)
    {
        return $any instanceof JsoupScriptComponent;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Web парсер (Jsoup)';
    }

    public function getIcon()
    {
        return 'develnext/bundle/jsoup/html16.png';
    }

    public function getIdPattern()
    {
        return "jsoup%s";
    }

    public function getGroup()
    {
        return 'Интернет и сеть';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return JsoupScript::class;
    }

    public function getDescription()
    {
        return 'Компонент для парсинга html и сайтов в стиле апи jQuery';
    }
}