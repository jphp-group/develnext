<?php
namespace ide\autocomplete\php;


use ide\autocomplete\AutoComplete;
use ide\autocomplete\AutoCompleteRegion;
use ide\autocomplete\AutoCompleteType;
use ide\autocomplete\ConstantAutoCompleteItem;
use ide\autocomplete\MethodAutoCompleteItem;
use ide\autocomplete\PropertyAutoCompleteItem;
use ide\autocomplete\StatementAutoCompleteItem;
use ide\autocomplete\VariableAutoCompleteItem;
use php\lib\Str;

class EventVariableAutoCompleteType extends AutoCompleteType
{
    
    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return StatementAutoCompleteItem[]
     */
    public function getStatements(AutoComplete $context, AutoCompleteRegion $region)
    {
        return [];
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return ConstantAutoCompleteItem[]
     */
    public function getConstants(AutoComplete $context, AutoCompleteRegion $region)
    {
        return [];
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return PropertyAutoCompleteItem[]
     */
    public function getProperties(AutoComplete $context, AutoCompleteRegion $region)
    {
        $result = [];

        $values = $region->getValues('variable');
        if ($values) {
            foreach ($values as $value) {
                if (($value['name'] == 'event' || $value['name'] == 'e') && Str::endsWith($value['type'], 'Event')) {
                    $result[] = new PropertyAutoCompleteItem('sender', 'Текущий объект');
                    $result[] = new PropertyAutoCompleteItem('target', 'Целевой объект');

                    switch ($value['type']) {
                        case 'UXMouseEvent':
                            $result[] = new PropertyAutoCompleteItem('x', 'Позиция курсора по X');
                            $result[] = new PropertyAutoCompleteItem('y', 'Позиция курсора по Y');
                            $result[] = new PropertyAutoCompleteItem('screenX', 'Глобальная позиция курсора по X');
                            $result[] = new PropertyAutoCompleteItem('screenY', 'Глобальная позиция курсора по Y');
                            $result[] = new PropertyAutoCompleteItem('clickCount', 'Количество кликов');
                            $result[] = new PropertyAutoCompleteItem('button', 'Тип кнопки (PRIMARY, SECONDARY или MIDDLE)');
                            $result[] = new PropertyAutoCompleteItem('altDown', 'Нажат ли Alt (true/false)');
                            $result[] = new PropertyAutoCompleteItem('controlDown', 'Нажат ли Ctrl (true/false)');
                            $result[] = new PropertyAutoCompleteItem('shiftDown', 'Нажат ли Shift (true/false)');
                            break;
                        case 'UXKeyEvent':
                            $result[] = new PropertyAutoCompleteItem('character', 'Символ');
                            $result[] = new PropertyAutoCompleteItem('text', 'Текст');
                            $result[] = new PropertyAutoCompleteItem('codeName', 'Код клавишы');
                            $result[] = new PropertyAutoCompleteItem('altDown', 'Нажат ли Alt (true/false)');
                            $result[] = new PropertyAutoCompleteItem('controlDown', 'Нажат ли Ctrl (true/false)');
                            $result[] = new PropertyAutoCompleteItem('shiftDown', 'Нажат ли Shift (true/false)');
                            break;
                        case 'UXScrollEvent':
                            $result[] = new PropertyAutoCompleteItem('deltaX', 'Смещение по X');
                            $result[] = new PropertyAutoCompleteItem('deltaY', 'Смещение по Y');
                            $result[] = new PropertyAutoCompleteItem('touchCount', 'Количество прокруток');
                            break;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return MethodAutoCompleteItem[]
     */
    public function getMethods(AutoComplete $context, AutoCompleteRegion $region)
    {
        return [
            new MethodAutoCompleteItem('consume', 'Рассмотреть это событие как последнее', 'consume()'),
            new MethodAutoCompleteItem('isConsume', 'Последнее событие?', 'isConsume()'),
        ];
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return VariableAutoCompleteItem[]
     */
    public function getVariables(AutoComplete $context, AutoCompleteRegion $region)
    {
        return [];
    }
}