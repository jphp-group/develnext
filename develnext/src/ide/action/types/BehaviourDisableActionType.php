<?php
namespace ide\action\types;

use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use ide\behaviour\AbstractBehaviourSpec;
use ide\behaviour\IdeBehaviourManager;
use ide\editors\argument\BehaviourTypeArgumentEditor;
use ide\editors\argument\EnumArgumentEditor;
use ide\editors\argument\MixedArgumentEditor;
use ide\editors\argument\ObjectArgumentEditor;
use ide\editors\common\ObjectListEditor;
use ide\editors\common\ObjectListEditorItem;
use ide\formats\form\AbstractFormElement;
use ide\Ide;
use ide\scripts\elements\MacroScriptComponent;
use php\gui\UXApplication;
use php\lib\Items;
use php\lib\Str;

class BehaviourDisableActionType extends AbstractSimpleActionType
{
    /**
     * @var IdeBehaviourManager
     */
    protected $manager;

    /**
     * CallScriptActionType constructor.
     */
    public function __construct()
    {
        $this->manager = new IdeBehaviourManager(null);
    }

    function attributes()
    {
        return [
            'object' => 'object',
            'behaviour' => 'string'
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Объект',
            'behaviour' => 'Поведение',
        ];
    }

    function attributeSettings()
    {
        return [
            'object' => ['def' => '~sender'],
            'behaviour' => [
                'editor' => function ($name, $label) {
                    return new BehaviourTypeArgumentEditor($this->manager);
                }
            ]
        ];
    }

    function getGroup()
    {
        return self::GROUP_CONTROL;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_BEHAVIOUR;
    }

    function getTagName()
    {
        return "behaviourDisable";
    }

    function getTitle(Action $action = null)
    {
        return 'Отключить поведение';
    }

    function getDescription(Action $action = null)
    {
        if (!$action) {
            return "Отключить поведение объекта";
        }

        $type = $action->behaviour;
        $spec = $this->manager->getBehaviourSpecByClass($type);

        return Str::format("Отключить поведение `%s` объекта %s", $spec ? $spec->getName() : 'Неизвестное', $action->get('object'));
    }

    function getIcon(Action $action = null)
    {
        return "icons/pluginRemove16.png";
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        return "\$this->behaviour({$action->get('object')}, {$action->get('behaviour')})->disable()";
    }
}