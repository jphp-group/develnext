<?php
namespace ide\behaviour\spec;

use behaviour\custom\BlinkAnimationBehaviour;
use behaviour\custom\ChatterAnimationBehaviour;
use ide\behaviour\AbstractBehaviourSpec;
use ide\formats\form\AbstractFormElement;
use ide\scripts\AbstractScriptComponent;
use php\gui\UXNode;

class ChatterAnimationBehaviourSpec extends AbstractBehaviourSpec
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Дергание';
    }

    public function getGroup()
    {
        return self::GROUP_ANIMATION;
    }

    public function getIcon()
    {
        return "icons/film16.png";
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Быстрое и хаотичное перемещение объекта вокруг своей позиции';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return ChatterAnimationBehaviour::class;
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