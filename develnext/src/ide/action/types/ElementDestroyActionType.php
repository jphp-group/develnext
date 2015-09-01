<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use php\lib\Str;

class ElementDestroyActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'object' => 'element',
        ];
    }

    function getTagName()
    {
        return 'elementDestroy';
    }

    function getTitle(Action $action)
    {
        return 'Уничтожить элемент';
    }

    function getDescription(Action $action)
    {
        return Str::format("Уничтожить (удалить) элемент %s", $action->get('object'));
    }

    function getIcon(Action $action)
    {

    }

    /**
     * @param Action $action
     * @return string
     */
    function convertToCode(Action $action)
    {
        $object = $action->get('object');

        return "unset({$object})";
    }
}