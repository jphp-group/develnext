<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\Str;

class ShowFormActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'form' => 'form',
        ];
    }

    function attributeLabels()
    {
        return [
            'form' => 'Название формы'
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
        return 'showForm';
    }

    function getTitle(Action $action = null)
    {
        return 'Открыть форму';
    }

    function getDescription(Action $action = null)
    {
        if ($action == null) {
            return "Открыть форму";
        }

        return Str::format("Открыть форму %s", $action->get('form'));
    }

    function getIcon(Action $action = null)
    {
        return 'icons/showForm16.png';
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $form = $action->get('form');

        return "app()->showForm({$form})";
    }
}