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

class TextMethodsArgumentEditor extends AbstractArgumentEditor
{
    static $variants = [
        'equals' => 'Равен',
        'equalsIgnoreCase' => 'Равен (без учета регистра)',
        'startsWith' => 'Начинается с',
        'endsWidth' => 'Кончается ...',
        'contains' => 'Содержит',
        'regex' => 'Регулярное выражение',
        'regexIgnoreCase' => 'Регулярное выражение (без учета регистра)',
    ];

    /** @var UXToggleGroup */
    protected $group;

    /**
     * @var UXComboBox
     */
    protected $comboBox;
    
    /**
     * @return string
     */
    public function getCode()
    {
        return 'textMethods';
    }

    public function isInline()
    {
        return true;
    }

    /**
     * @param null $label
     * @return UXNode
     */
    public function makeUi($label = null)
    {
        $this->comboBox = new UXComboBox();
        $this->comboBox->items->addAll(self::$variants);

        $labelUi = new UXLabel($label);
        $labelUi->style = '-fx-font-style: italic;';
        $labelUi->height = 27;

        $box = new UXHBox([$labelUi, $this->comboBox]);
        $box->spacing = 10;
        $box->paddingLeft = 50;

        return $box;
    }

    public function requestUiFocus()
    {
        $this->comboBox->requestFocus();
    }

    public function getValue()
    {
        $index = $this->comboBox->selectedIndex;

        $result = Items::keys(self::$variants)[$index];

        if (!$result) {
            $result = Items::firstKey(self::$variants);
        }

        return $result;
    }

    public function setValue($value, $type)
    {
        parent::setValue($value, $type);

        if ($value) {
            $this->comboBox->selected = self::$variants[$value];
        } else {
            $this->comboBox->selectedIndex = 0;
        }
    }
}