<?php
namespace ide\behaviour\spec;

use behaviour\custom\BlinkAnimationBehaviour;
use behaviour\custom\FadeAnimationBehaviour;
use behaviour\custom\ScaleAnimationBehaviour;
use ide\behaviour\AbstractBehaviourSpec;
use ide\formats\form\elements\FormFormElement;
use ide\scripts\AbstractScriptComponent;
use php\gui\effect\UXDropShadowEffect;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\UXNode;

/**
 * @package ide\behaviour\spec
 */
class ScaleAnimationBehaviourSpec extends AbstractBehaviourSpec
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Масштабирование';
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
        return 'Изменение масштаба объекта (scale)';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return ScaleAnimationBehaviour::class;
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