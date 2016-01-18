<?php
namespace ide\formats\form\event;

use php\gui\event\UXKeyEvent;

/**
 * Class KeyEventKind
 * @package ide\formats\form\event
 */
class KeyEventKind extends AbstractEventKind
{
    /**
     * @return array
     */
    public function getArguments()
    {
        return [
            [UXKeyEvent::class, 'event', 'null']
        ];
    }
}