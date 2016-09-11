<?php
namespace ide\behaviour\spec;

use behaviour\custom\AutoDestroyBehaviour;
use behaviour\custom\BlinkAnimationBehaviour;
use behaviour\custom\ChatterAnimationBehaviour;
use behaviour\custom\CursorBindBehaviour;
use behaviour\custom\DraggingFormBehaviour;
use ide\behaviour\AbstractBehaviourSpec;
use ide\formats\form\AbstractFormElement;
use ide\scripts\AbstractScriptComponent;
use php\gui\UXNode;

class AutoDestroyBehaviourSpec extends AbstractBehaviourSpec
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Автоматическое уничтожение';
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
        return 'Объект автоматически уничтожит себя через N млсек.';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return AutoDestroyBehaviour::class;
    }

    /**
     * @param $target
     * @return bool
     */
    public function isAllowedFor($target)
    {
        return true;
    }
}