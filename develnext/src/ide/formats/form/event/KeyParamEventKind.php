<?php
namespace ide\formats\form\event;

use ide\editors\AbstractEditor;
use php\gui\event\UXKeyEvent;

class KeyParamEventKind extends KeyEventKind
{
    public function getParamVariants(AbstractEditor $contextEditor = null)
    {
        $letters = [
            'A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D', 'E' => 'E', 'F' => 'F', 'G' => 'G', 'H' => 'H', 'I' => 'I',
            'J' => 'J', 'K' => 'K', 'L' => 'L', 'M' => 'M', 'N' => 'N', 'O' => 'O', 'P' => 'P', 'Q' => 'Q', 'R' => 'R',
            'S' => 'S', 'T' => 'T', 'U' => 'U', 'V' => 'V', 'W' => 'W', 'X' => 'X', 'Y' => 'Y', 'Z' => 'Z',
            '-',
            'Любая буква' => 'AnyLetter'
        ];

        $digits = [
            0, 1, 2, 3, 4, 5, 6, 7, 8, 9, '-', 'Любая цифра' => 'AnyDigit'
        ];

        $funcKeys = [
            'F1' => 'F1', 'F2' => 'F2', 'F3' => 'F3', 'F4' => 'F4', 'F5' => 'F5',
            'F6' => 'F6', 'F7' => 'F7', 'F8' => 'F8', 'F9' => 'F9', 'F10' => 'F10', 'F11' => 'F11', 'F12' => 'F12',
            '-',
            'F1-F12' => 'AnyF'
        ];

        $multimedia = [
            'Играть' => 'Play',
            'Запись' => 'Record',
            'Перемотка' => 'Rewind',
            'Предыдущий трек' => 'PreviousTrack',
            'Следующий трек' => 'NextTrack',
            '-',
            'Прибавить громкость' => 'VolumeUp',
            'Убавить громкость' => 'VolumeDown',
            'Выключить звук' => 'Mute'
        ];

        $others = [
            'Пробел' => 'Space',
            'Enter' => 'Enter',
            'Delete' => 'Delete',
            'Таб' => 'Tab',
            'Print Screen' => 'PrintScreen',
        ];

        $directions = [
            'Лево' => 'Left',
            'Право' => 'Right',
            'Верх' => 'Up',
            'Низ' => 'Down',
            '-',
            'Любое направление' => 'AnyDirection'
        ];

        $variants = [
            'Направление' => $directions,
            'Буквы' => $letters,
            'Цифры' => $digits,
            'Функциональные' => $funcKeys,
            'Другие' => $others,
        ];

        $ctrLetters = [];
        $altLetters = [];
        $shiftLetters = [];

        foreach ($variants as $group => $codes) {
            foreach ($codes as $code => $name) {
                if ($name === '-') continue;

                $ctrLetters[$group]['Ctrl + ' . $code] = 'Ctrl+' . $name;
            }
        }

        foreach ($variants as $group => $codes) {
            foreach ($codes as $code => $name) {
                if ($name === '-') continue;

                $altLetters[$group]['Alt + ' . $code] = 'Alt+' . $name;
            }
        }

        foreach ($variants as $group => $codes) {
            foreach ($codes as $code => $name) {
                if ($name === '-') continue;

                $shiftLetters[$group]['Shift + ' . $code] = 'Shift+' . $name;
            }
        }

        return [
            'Любая кнопка' => '',
            '-',
            'Пробел' => 'Space',
            'Enter' => 'Enter',
            'Escape' => 'Esc',
            '-',
            'Направление' => $directions,
            'Цифры' => $digits,
            'Буквы' => $letters,
            'Функциональные' => $funcKeys,
            //'Мультимедиа' => $multimedia,
            'Другие' => [
                'Таб' => 'Tab',
                'Backspace' => 'Backspace',
                'Delete' => 'Delete',
                'Insert' => 'Insert',
                'Pause' => 'Pause',
                'Print Screen' => 'PrintScreen',
            ],
            '-',
            'Ctrl + ?' => $ctrLetters,
            'Alt + ?' => $altLetters,
            'Shift + ?' => $shiftLetters,
        ];
    }

}