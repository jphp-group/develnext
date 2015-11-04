<?php
namespace ide\editors\argument;

use php\gui\layout\UXHBox;
use php\gui\UXCheckbox;
use php\gui\UXComboBox;
use php\gui\UXLabel;
use php\gui\UXNode;
use php\gui\UXToggleButton;
use php\gui\UXToggleGroup;
use php\lib\Items;
use php\lib\Str;

class TextMethodsArgumentEditor extends MethodsArgumentEditor
{
    static $variants = [
        'equals' => 'Равно',
        'equalsIgnoreCase' => 'Равно (без учета регистра)',
        'startsWith' => 'Начинается с',
        'endsWidth' => 'Кончается ...',
        'contains' => 'Содержит',
        'regex' => 'Регулярное выражение',
        'regexIgnoreCase' => 'Регулярное выражение (без учета регистра)',
        'smaller' => 'Меньше',
        'greater' => 'Больше',
    ];

    public function __construct(array $options = [])
    {
        parent::__construct(self::$variants);
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return 'textMethods';
    }
}