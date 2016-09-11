<?php
namespace ide\behaviour\spec;

use behaviour\custom\BlinkAnimationBehaviour;
use behaviour\custom\RandomMovementAnimationBehaviour;
use ide\behaviour\AbstractBehaviourSpec;
use ide\formats\form\elements\FormFormElement;
use ide\scripts\AbstractScriptComponent;
use php\gui\UXNode;

class RandomMovementAnimationBehaviourSpec extends AbstractBehaviourSpec
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Случайные перемещения';
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
        return 'Объект перемещается в случайные точки в рамках объекта, на котором находится';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return RandomMovementAnimationBehaviour::class;
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