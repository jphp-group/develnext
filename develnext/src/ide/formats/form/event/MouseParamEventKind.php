<?php
namespace ide\formats\form\event;

use php\gui\event\UXMouseEvent;

class MouseParamEventKind extends MouseEventKind
{
    public function getParamVariants()
    {
        return [
            'Любая кнопка' => '',
            '-',
            'Левая кнопка' => 'Left',
            'Правая кнопка' => 'Right',
            'Средняя кнопка' => 'Middle',
            '-',
            'Двойное нажатие' => '2x',
            'Тройное нажатие' => '3x',
        ];
    }
}