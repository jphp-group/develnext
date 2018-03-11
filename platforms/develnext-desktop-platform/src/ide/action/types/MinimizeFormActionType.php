<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\Str;

class MinimizeFormActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'form' => 'form',
            'restore' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'form' => 'Форма',
            'restore' => 'Вернуть обратно свернутую форму',
        ];
    }

    function attributeSettings()
    {
        return [
            'object' => ['def' => '~senderForm'],
        ];
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_WINDOW;
    }

    function getGroup()
    {
        return self::GROUP_CONTROL;
    }

    function getTagName()
    {
        return 'minimizeForm';
    }

    function getTitle(Action $action = null)
    {
        return !$action ?
            'Свернуть форму'
            : ($action->restore ? 'Вернуть свернутую форму' : 'Свернуть форму');
    }

    function getDescription(Action $action = null)
    {
        if (!$action) {
            return "Свернуть форму в таск бар";
        }

        if ($action->restore) {
            return Str::format("Вернуть свернутую форму %s из таск бара", $action->get('form'));
        } else {
            return Str::format("Свернуть форму %s в таск бар", $action->get('form'));
        }
    }

    function getIcon(Action $action = null)
    {
        return 'icons/minimizeForm16.png';
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $form = $action->get('form');

        if (!$action->restore) {
            return "app()->minimizeForm({$form})";
        } else {
            return "app()->restoreForm({$form})";
        }
    }
}