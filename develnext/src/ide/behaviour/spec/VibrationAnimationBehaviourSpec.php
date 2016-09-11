<?php
namespace ide\behaviour\spec;

use behaviour\custom\BlinkAnimationBehaviour;
use behaviour\custom\VibrationAnimationBehaviour;
use ide\behaviour\AbstractBehaviourSpec;
use ide\scripts\AbstractScriptComponent;
use php\gui\UXNode;

/**
 * Class VibrationAnimationBehaviourSpec
 * @package ide\behaviour\spec
 */
class VibrationAnimationBehaviourSpec extends AbstractBehaviourSpec
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Колебания';
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
        return 'Колебания объекта по горизонтали и/или вертикали';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return VibrationAnimationBehaviour::class;
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