<?php
namespace ide\behaviour\spec;

use behaviour\custom\BlinkAnimationBehaviour;
use behaviour\custom\PulseAnimationBehaviour;
use ide\behaviour\AbstractBehaviourSpec;
use ide\formats\form\elements\FormFormElement;
use ide\scripts\AbstractScriptComponent;
use php\gui\effect\UXDropShadowEffect;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\UXNode;

/**
 * @package ide\behaviour\spec
 */
class PulseAnimationBehaviourSpec extends AbstractBehaviourSpec
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Пульсация';
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
        return 'Плавное увеличение и уменьшение масштаба объекта';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return PulseAnimationBehaviour::class;
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