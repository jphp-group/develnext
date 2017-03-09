<?php
namespace ide\formats\form\event;

use php\gui\event\UXMouseEvent;

/**
 * Class MouseEventKind
 * @package ide\formats\form\event
 */
class MouseEventKind extends AbstractEventKind
{
    /**
     * @return array
     */
    public function getArguments()
    {
        return [
            [UXMouseEvent::class, 'e', 'null']
        ];
    }
}