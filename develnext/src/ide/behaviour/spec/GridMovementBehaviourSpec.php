<?php
namespace ide\behaviour\spec;

use behaviour\custom\BlinkAnimationBehaviour;
use behaviour\custom\ChatterAnimationBehaviour;
use behaviour\custom\DraggingFormBehaviour;
use behaviour\custom\GridMovementBehaviour;
use ide\behaviour\AbstractBehaviourSpec;
use ide\formats\form\AbstractFormElement;
use ide\scripts\AbstractScriptComponent;
use php\gui\UXNode;

class GridMovementBehaviourSpec extends AbstractBehaviourSpec
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Перемещение по сетке';
    }

    public function getGroup()
    {
        return self::GROUP_INPUT;
    }

    public function getIcon()
    {
        return "icons/input16.png";
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Перемещение объекта по фиксированной сетке с помощью стрелок';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return GridMovementBehaviour::class;
    }

    /**
     * @param $target
     * @return bool
     */
    public function isAllowedFor($target)
    {
        return !($target instanceof AbstractScriptComponent);
    }
}