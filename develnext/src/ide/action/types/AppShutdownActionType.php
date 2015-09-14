<?php
namespace ide\action\types;

use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use php\gui\framework\Application;

/**
 * Class AppShutdownActionType
 * @package ide\action\types
 */
class AppShutdownActionType extends AbstractSimpleActionType
{
    function getTagName()
    {
        return 'applicationShutdown';
    }

    function getGroup()
    {
        return self::GROUP_APP;
    }

    function getTitle(Action $action = null)
    {
        return 'Выход из программы';
    }

    function getDescription(Action $action = null)
    {
        return 'Полностью закрыть все окна программы и выйти из неё';
    }

    function getIcon(Action $action = null)
    {
        return 'icons/shutdown16.png';
    }

    function imports()
    {
        return [
            Application::class
        ];
    }

    /**
     * @param Action $action
     * @return string
     */
    function convertToCode(Action $action)
    {
        return 'Application::get()->shutdown()';
    }
}