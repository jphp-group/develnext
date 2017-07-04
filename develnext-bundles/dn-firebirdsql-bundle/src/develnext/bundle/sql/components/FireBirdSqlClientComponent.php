<?php
namespace develnext\bundle\sql\components;

use bundle\sql\FireBirdSqlClient;
use ide\Ide;
use ide\scripts\AbstractScriptComponent;

class FireBirdSqlClientComponent extends AbstractScriptComponent
{
    public function isOrigin($any)
    {
        return $any instanceof FireBirdSqlClientComponent;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'FireBird SQL клиент';
    }

    public function getIcon()
    {
        return 'develnext/bundle/sql/database16.png';
    }

    public function getIdPattern()
    {
        return "database%s";
    }

    public function getGroup()
    {
        return 'Данные';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return FireBirdSqlClient::class;
    }

    public function getDescription()
    {
        return 'Компонент клиент для работы с firebird базой данных.';
    }

    public function createElement()
    {
        //Ide::toast('FireBird Клиент не поддерживает WireCrypt авторизацию из версии 3.0');

        return parent::createElement();
    }
}