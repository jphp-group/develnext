<?php
namespace ide\webplatform\formats\form\event;

use ide\editors\AbstractEditor;

/**
 * Class WebMouseEventKind
 * @package ide\webplatform\formats\form\event
 */
class WebMouseEventKind extends WebEventKind
{
    public function getParamVariants(AbstractEditor $contextEditor = null)
    {
        return [
            'Любая кнопка' => '',
            '-',
            'Левая кнопка' => 'Left',
            'Правая кнопка' => 'Right',
            'Средняя кнопка' => 'Middle',
            '-',
            'Двойное нажатие' => '2x',
        ];
    }
}