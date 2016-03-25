<?php
namespace ide\behaviour\spec;

use behaviour\custom\BlinkAnimationBehaviour;
use behaviour\custom\ChatterAnimationBehaviour;
use behaviour\custom\DraggingBehaviour;
use behaviour\custom\DraggingFormBehaviour;
use behaviour\custom\GameEntityBehaviour;
use behaviour\custom\GameSceneBehaviour;
use behaviour\custom\WrapScreenBehaviour;
use ide\behaviour\AbstractBehaviourSpec;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\elements\FormFormElement;
use ide\formats\form\elements\GamePaneFormElement;
use ide\formats\form\elements\PanelFormElement;
use ide\formats\form\elements\ScrollPaneFormElement;
use ide\formats\form\elements\SpriteViewFormElement;
use ide\scripts\AbstractScriptComponent;
use php\gui\UXNode;

class WrapScreenBehaviourSpec extends AbstractBehaviourSpec
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Возвращаемость';
    }

    public function getGroup()
    {
        return self::GROUP_GAME;
    }

    public function getIcon()
    {
        return "icons/gameMonitor16.png";
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Объект будет огибать видимую область и возвращаться с другой стороны';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return WrapScreenBehaviour::class;
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