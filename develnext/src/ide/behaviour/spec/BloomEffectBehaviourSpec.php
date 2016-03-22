<?php
namespace ide\behaviour\spec;

use behaviour\custom\BlinkAnimationBehaviour;
use behaviour\custom\BloomEffectBehaviour;
use behaviour\custom\ChatterAnimationBehaviour;
use behaviour\custom\DraggingBehaviour;
use behaviour\custom\DraggingFormBehaviour;
use behaviour\custom\DropShadowEffectBehaviour;
use behaviour\custom\ReflectionEffectBehaviour;
use ide\behaviour\AbstractBehaviourSpec;
use ide\formats\form\AbstractFormElement;
use ide\scripts\AbstractScriptComponent;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\framework\behaviour\custom\EffectBehaviour;
use php\gui\UXNode;
use php\lib\reflect;

class BloomEffectBehaviourSpec extends AbstractEffectBehaviourSpec
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Свечение (Bloom)';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Объект начинает светиться';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return BloomEffectBehaviour::class;
    }
}