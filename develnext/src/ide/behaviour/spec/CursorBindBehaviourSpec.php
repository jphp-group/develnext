<?php
namespace ide\behaviour\spec;

use behaviour\custom\BlinkAnimationBehaviour;
use behaviour\custom\ChatterAnimationBehaviour;
use behaviour\custom\CursorBindBehaviour;
use behaviour\custom\DraggingFormBehaviour;
use ide\behaviour\AbstractBehaviourSpec;
use ide\formats\form\AbstractFormElement;
use ide\scripts\AbstractScriptComponent;
use php\gui\UXNode;

class CursorBindBehaviourSpec extends AbstractBehaviourSpec
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Курсорный объект';
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
        return 'Объект будет следовать за курсором, т.е. вести себя как курсор';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return CursorBindBehaviour::class;
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