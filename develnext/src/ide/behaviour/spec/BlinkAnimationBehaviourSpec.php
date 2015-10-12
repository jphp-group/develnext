<?php
namespace ide\behaviour\spec;

use behaviour\custom\BlinkAnimationBehaviour;
use ide\behaviour\AbstractBehaviourSpec;
use ide\scripts\AbstractScriptComponent;
use php\gui\UXNode;

/**
 * Class BlinkAnimationBehaviourSpec
 * @package ide\behaviour\spec
 */
class BlinkAnimationBehaviourSpec extends AbstractBehaviourSpec
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Мигание';
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
        return 'Плавное мигание объекта';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return BlinkAnimationBehaviour::class;
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