<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\Str;

class LoadFormActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'form' => 'form',
            'saveSize' => 'flag',
            'savePosition' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'form' => 'Название формы',
            'saveSize' => 'Сохранить размеры',
            'savePosition' => 'Сохранить позицию',
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
        return 'loadForm';
    }

    function getTitle(Action $action = null)
    {
        return 'Загрузить форму';
    }

    function getDescription(Action $action = null)
    {
        if ($action == null) {
            return "Загрузить форму (а текущую закрыть)";
        }

        $arg = '';

        if ($action->saveSize) {
            $arg = ', сохраняя размеры';
        }

        if ($action->savePosition) {
            $arg .= ', сохраняя позицию';
        }

        return Str::format("Загрузить форму %s, а текущую закрыть%s", $action->get('form'), $arg);
    }

    function getIcon(Action $action = null)
    {
        return 'icons/loadForm16.png';
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $form = $action->get('form');

        $saveSize = $action->saveSize ? 'true' : 'false';
        $savePosition = $action->savePosition ? 'true' : 'false';

        if (!$action->saveSize && !$action->savePosition) {
            return "\$this->loadForm({$form})";
        } else {
            return "\$this->loadForm({$form}, $saveSize, $savePosition)";
        }
    }
}