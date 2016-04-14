<?php
namespace ide\behaviour\spec;

use behaviour\custom\BlinkAnimationBehaviour;
use behaviour\custom\FadeAnimationBehaviour;
use ide\behaviour\AbstractBehaviourSpec;
use ide\scripts\AbstractScriptComponent;
use php\gui\effect\UXDropShadowEffect;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\UXNode;

/**
 * Class BlinkAnimationBehaviourSpec
 * @package ide\behaviour\spec
 */
class FadeAnimationBehaviourSpec extends AbstractBehaviourSpec
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Затухание';
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
        return 'Изменение прозрачности объекта (fade анимация)';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return FadeAnimationBehaviour::class;
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