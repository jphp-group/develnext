<?php
namespace develnext\bundle\sql\components;

use bundle\sql\MsSqlClient;
use ide\scripts\AbstractScriptComponent;

class MsSqlClientComponent extends AbstractScriptComponent
{
    public function isOrigin($any)
    {
        return $any instanceof MsSqlClientComponent;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Microsoft SQL клиент';
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
        return MsSqlClient::class;
    }

    public function getDescription()
    {
        return 'Компонент клиент для работы с Microsoft SQL базой данных.';
    }
}