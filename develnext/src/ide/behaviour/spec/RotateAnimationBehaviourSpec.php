<?php
namespace ide\behaviour\spec;

use behaviour\custom\BlinkAnimationBehaviour;
use behaviour\custom\RotateAnimationBehaviour;
use ide\behaviour\AbstractBehaviourSpec;
use ide\formats\form\elements\FormFormElement;
use ide\scripts\AbstractScriptComponent;
use php\gui\UXNode;

class RotateAnimationBehaviourSpec extends AbstractBehaviourSpec
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Вращение';
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
        return 'Плавное вращение объекта';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return RotateAnimationBehaviour::class;
    }

    /**
     * @param $target
     * @return bool
     */
    public function isAllowedFor($target)
    {
        return !($target instanceof AbstractScriptComponent)
            && !($target instanceof FormFormElement);
    }
}