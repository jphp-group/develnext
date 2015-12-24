<?php
namespace ide\behaviour\spec;

use behaviour\custom\BlinkAnimationBehaviour;
use behaviour\custom\ChatterAnimationBehaviour;
use behaviour\custom\DraggingBehaviour;
use behaviour\custom\DraggingFormBehaviour;
use behaviour\custom\KeyInputRuleBehaviour;
use ide\behaviour\AbstractBehaviourSpec;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\elements\TextAreaFormElement;
use ide\formats\form\elements\TextFieldFormElement;
use ide\scripts\AbstractScriptComponent;
use php\gui\UXNode;
use php\gui\UXTextInputControl;

class KeyInputRuleBehaviourSpec extends AbstractBehaviourSpec
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Ограничение ввода символов';
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
        return 'Ограничивает ввод только определенными заданными символами';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return KeyInputRuleBehaviour::class;
    }

    /**
     * @param $target
     * @return bool
     */
    public function isAllowedFor($target)
    {
        if (!($target instanceof AbstractFormElement)) {
            return false;
        }

        return $target->createElement() instanceof UXTextInputControl;
    }
}