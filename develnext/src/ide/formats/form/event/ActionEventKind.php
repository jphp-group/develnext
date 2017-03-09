<?php
namespace ide\formats\form\event;

use php\gui\event\UXEvent;

/**
 * Class ActionEventKind
 * @package ide\formats\form\event
 */
class ActionEventKind extends AbstractEventKind
{
    /**
     * @return array
     */
    public function getArguments()
    {
        return [
            [UXEvent::class, 'e', 'null']
        ];
    }
}