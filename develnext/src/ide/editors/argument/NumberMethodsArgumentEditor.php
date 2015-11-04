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

class NumberMethodsArgumentEditor extends MethodsArgumentEditor
{
    static $variants = [
        'equals' => 'Равен',
        'smaller' => 'Меньше',
        'greater' => 'Больше',
        'mod' => 'Делится без остатка'
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
        return 'numberMethods';
    }
}