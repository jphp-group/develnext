<?php
namespace ide\formats\form\event;

use behaviour\custom\GameEntityBehaviour;
use ide\behaviour\spec\GameEntityBehaviourSpec;
use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use php\game\event\UXCollisionEvent;

class CollisionParamEventKind extends AbstractEventKind
{
    public function getParamVariants(AbstractEditor $contextEditor = null)
    {
        $result = [];

        $result['Только для объектов игровой сцены'] = false;
        $result[] = '-';

        if ($contextEditor instanceof FormEditor) {
            foreach ($contextEditor->getObjectList() as $item) {
                $behaviours = $contextEditor->getBehaviourManager()->getBehaviours($item->value);

                if ($behaviours[GameEntityBehaviour::class]) {
                    $result[$item->text] = $item->value;
                } elseif ($item->element) {
                    $spec = $contextEditor->getBehaviourManager()->getBehaviourSpecByClass(GameEntityBehaviour::class);

                    if ($spec && $spec->isAllowedFor($item->element)) {
                        $result[$item->text . " (недоступно)"] = false; //$item->value;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return [
            [UXCollisionEvent::class, 'e', 'null']
        ];
    }
}