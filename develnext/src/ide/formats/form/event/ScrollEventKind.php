<?php
namespace ide\formats\form\event;

use php\gui\event\UXScrollEvent;

class ScrollEventKind extends AbstractEventKind
{
    /**
     * @return array
     */
    public function getArguments()
    {
        return [
            [UXScrollEvent::class, 'event', 'null']
        ];
    }
}