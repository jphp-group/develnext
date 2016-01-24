<?php
namespace ide\formats\form\event;

use ide\editors\AbstractEditor;
use php\gui\event\UXScrollEvent;

class ScrollParamEventKind extends ScrollEventKind
{
    public function getParamVariants(AbstractEditor $contextEditor = null)
    {
        return [
            'Любое направление' => '',
            '-',
            'Прокрутка вверх' => 'Up',
            'Прокрутка вниз' => 'Down',
        ];
    }
}