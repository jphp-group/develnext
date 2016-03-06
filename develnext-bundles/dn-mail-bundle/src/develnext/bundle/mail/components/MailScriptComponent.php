<?php
namespace develnext\bundle\mail\components;

use ide\scripts\AbstractScriptComponent;
use script\MailScript;

class MailScriptComponent extends AbstractScriptComponent
{
    public function isOrigin($any)
    {
        return $any instanceof MailScriptComponent;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Отправитель писем';
    }

    public function getIcon()
    {
        return 'develnext/bundle/mail/mail16.png';
    }

    public function getIdPattern()
    {
        return "mail%s";
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
        return MailScript::class;
    }

    public function getDescription()
    {
        return 'Компонент для отправки электронных писем';
    }
}