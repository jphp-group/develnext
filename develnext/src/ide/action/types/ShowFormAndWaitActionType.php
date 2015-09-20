<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\Str;

class ShowFormAndWaitActionType extends AbstractSimpleActionType
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
        return self::GROUP_UI;
    }

    function getTagName()
    {
        return 'showFormAndWait';
    }

    function getTitle(Action $action = null)
    {
        return 'Открыть форму и ждать';
    }

    function getDescription(Action $action = null)
    {
        if ($action == null) {
            return "Открыть форму и ждать её закрытия";
        }

        return Str::format("Открыть форму %s и ждать её закрытия", $action->get('form'));
    }

    function getIcon(Action $action = null)
    {
        return 'icons/showFormAndWait16.png';
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $form = $action->get('form');

        return "app()->showFormAndWait({$form})";
    }
}