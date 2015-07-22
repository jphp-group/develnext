<?php
namespace ide\formats\form\event;

use php\gui\event\UXWindowEvent;

/**
 * Class WindowEventKind
 * @package ide\formats\form\event
 */
class WindowEventKind extends AbstractEventKind
{
    /**
     * @return array
     */
    public function getArguments()
    {
        return [
            [UXWindowEvent::class, 'event']
        ];
    }
}