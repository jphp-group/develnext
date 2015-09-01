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

    function getTitle(Action $action)
    {
        return 'Выход из программы';
    }

    function getDescription(Action $action)
    {
        return 'Полностью закрыть все окна программы и выйти из неё';
    }

    function getIcon(Action $action)
    {
        return null;
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