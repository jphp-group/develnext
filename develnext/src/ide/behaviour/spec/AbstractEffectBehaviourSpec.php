<?php
namespace ide\behaviour\spec;

use behaviour\custom\BlinkAnimationBehaviour;
use behaviour\custom\ChatterAnimationBehaviour;
use behaviour\custom\DraggingBehaviour;
use behaviour\custom\DraggingFormBehaviour;
use behaviour\custom\DropShadowEffectBehaviour;
use ide\behaviour\AbstractBehaviourSpec;
use ide\formats\form\AbstractFormElement;
use ide\Logger;
use ide\scripts\AbstractScriptComponent;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\framework\behaviour\custom\EffectBehaviour;
use php\gui\UXNode;
use php\lib\reflect;

abstract class AbstractEffectBehaviourSpec extends AbstractBehaviourSpec
{
    public function getGroup()
    {
        return self::GROUP_EFFECT;
    }

    public function getIcon()
    {
        return "icons/blur16.png";
    }

    /**
     * @param $target
     * @return bool
     */
    public function isAllowedFor($target)
    {
        return !($target instanceof AbstractScriptComponent);
    }

    public function refreshNode(UXNode $node, AbstractBehaviour $behaviour)
    {
        if ($behaviour instanceof EffectBehaviour) {
            $this->deleteSelf($node, $behaviour);

            if ($behaviour->enabled && $behaviour->when == 'ALWAYS') {
                $effect = $behaviour->makeEffect();
                $behaviour->updateEffect($effect);
                $node->effects->add($effect);
            }
        }
    }

    public function deleteSelf(UXNode $node, AbstractBehaviour $behaviour)
    {
        if ($behaviour instanceof EffectBehaviour) {
            $removed = [];
            $testEffect = $behaviour->makeEffect();

            foreach ($node->effects as $effect) {
                if (get_class($effect) == get_class($testEffect)) {
                    $removed[] = $effect;
                }
            }

            foreach ($removed as $one) {
                $node->effects->remove($one);
            }
        }
    }
}