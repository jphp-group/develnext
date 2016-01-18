<?php
namespace ide\formats\form\event;

use php\gui\event\UXEvent;
use php\gui\framework\ScriptEvent;

/**
 * Class ObjectEventKind
 * @package ide\formats\form\event
 */
class ObjectEventKind extends AbstractEventKind
{
    /**
     * @return array
     */
    public function getArguments()
    {
        return [
            [ScriptEvent::class, 'event', 'null']
        ];
    }
}