<?php
namespace ide\behaviour\spec;

use behaviour\custom\BlinkAnimationBehaviour;
use behaviour\custom\BloomEffectBehaviour;
use behaviour\custom\ChatterAnimationBehaviour;
use behaviour\custom\DraggingBehaviour;
use behaviour\custom\DraggingFormBehaviour;
use behaviour\custom\DropShadowEffectBehaviour;
use behaviour\custom\GlowEffectBehaviour;
use behaviour\custom\ReflectionEffectBehaviour;
use ide\behaviour\AbstractBehaviourSpec;
use ide\formats\form\AbstractFormElement;
use ide\scripts\AbstractScriptComponent;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\framework\behaviour\custom\EffectBehaviour;
use php\gui\UXNode;
use php\lib\reflect;

class GlowEffectBehaviourSpec extends AbstractEffectBehaviourSpec
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Рассеянное свечение (Glow)';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Добавляет изображению эффект рассеяного свечения';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return GlowEffectBehaviour::class;
    }
}