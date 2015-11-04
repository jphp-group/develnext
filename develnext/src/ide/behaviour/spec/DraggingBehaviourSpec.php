<?php
namespace ide\behaviour\spec;

use behaviour\custom\BlinkAnimationBehaviour;
use behaviour\custom\ChatterAnimationBehaviour;
use behaviour\custom\DraggingBehaviour;
use behaviour\custom\DraggingFormBehaviour;
use ide\behaviour\AbstractBehaviourSpec;
use ide\formats\form\AbstractFormElement;
use ide\scripts\AbstractScriptComponent;
use php\gui\UXNode;

class DraggingBehaviourSpec extends AbstractBehaviourSpec
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Таскание объекта';
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
        return 'Возможность такскать объект с помощью мышки';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return DraggingBehaviour::class;
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