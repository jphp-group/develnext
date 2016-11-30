<?php
namespace ide\behaviour\spec;

use behaviour\custom\BlinkAnimationBehaviour;
use behaviour\custom\ChatterAnimationBehaviour;
use behaviour\custom\DraggingFormBehaviour;
use behaviour\custom\WidgetFormBehaviour;
use ide\behaviour\AbstractBehaviourSpec;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\elements\FormFormElement;
use ide\scripts\AbstractScriptComponent;
use php\gui\UXNode;

class WidgetFormBehaviourSpec extends AbstractBehaviourSpec
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Виджет для формы';
    }

    public function getGroup()
    {
        return self::GROUP_INPUT;
    }

    public function getIcon()
    {
        return "icons/input16.png";
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Оборачивает форму в виджет окно';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return WidgetFormBehaviour::class;
    }

    /**
     * @param $target
     * @return bool
     */
    public function isAllowedFor($target)
    {
        return $target instanceof FormFormElement;
    }
}