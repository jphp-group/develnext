<?php
namespace ide\formats\form\event;

use php\gui\event\UXScrollEvent;

class ScrollParamEventKind extends ScrollEventKind
{
    public function getParamVariants()
    {
        return [
            'Любое направление' => '',
            '-',
            'Прокрутка вверх' => 'Up',
            'Прокрутка вниз' => 'Down',
        ];
    }
}