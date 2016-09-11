<?php
namespace ide\behaviour\spec;

use behaviour\custom\BlinkAnimationBehaviour;
use behaviour\custom\ChatterAnimationBehaviour;
use behaviour\custom\CursorBindBehaviour;
use behaviour\custom\DraggingFormBehaviour;
use behaviour\custom\WatchMakerBehaviour;
use ide\behaviour\AbstractBehaviourSpec;
use ide\formats\form\AbstractFormElement;
use ide\scripts\AbstractScriptComponent;
use php\gui\UXNode;

class WatchMakerBehaviourSpec extends AbstractBehaviourSpec
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Временное табло';
    }

    public function getGroup()
    {
        return self::GROUP_LOGIC;
    }

    public function getIcon()
    {
        return "icons/fire16.png";
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'На объекте если возможно будет отображаться текущее время в выбранном формате';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return WatchMakerBehaviour::class;
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