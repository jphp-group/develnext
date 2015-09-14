<?php
namespace ide\editors\argument;

use ide\editors\common\FormListEditor;
use ide\editors\common\ObjectListEditor;
use ide\editors\common\ObjectListEditorItem;
use ide\editors\FormEditor;
use ide\editors\ScriptModuleEditor;
use ide\Ide;
use php\gui\layout\UXHBox;
use php\gui\UXApplication;
use php\gui\UXComboBox;
use php\gui\UXListCell;
use php\gui\UXNode;
use php\gui\UXTextField;

class MixedArgumentEditor extends AbstractArgumentEditor
{
    /** @var  UXHBox */
    protected $box;

    /** @var UXTextField */
    protected $input;

    /** @var UXComboBox */
    protected $typeSelect;

    /** @var ObjectListEditor */
    protected $objectListEditor;

    /** @var ObjectListEditor */
    protected $formListEditor;

    public function getCode()
    {
        return "mixed";
    }

    protected function valueTypes()
    {
        return ['string', 'magicString', 'integer', 'float', 'object', 'form', 'variable', 'expr'];
    }

    protected function valueTypeLabels()
    {
        return [
            'string' => 'Строка',
            'magicString' => 'Строка с переменными',
            'object' => 'Объект',
            'form' => 'Форма',
            'variable' => 'Переменная',
            'expr' => 'PHP код',
            'integer' => 'Число',
            'float' => 'Дробное число',
            'boolean' => 'Логика'
        ];
    }

    /**
     * @param null $label
     * @return UXNode
     */
    public function makeUi($label = null)
    {
        $this->formListEditor = new FormListEditor();
        $this->formListEditor->getUi()->maxWidth = 9999;
        $this->formListEditor->onChange(function ($value) {
            $this->input->text = $value;
        });

        $this->objectListEditor = new ObjectListEditor();

        if ($this->userData instanceof FormEditor) {
            $this->objectListEditor->enableSender();

            if (!($this->userData instanceof ScriptModuleEditor)) {
                $this->objectListEditor->enableAllForms();
            }

            $this->objectListEditor->build();
        }

        $this->objectListEditor->getUi()->maxWidth = 9999;
        $this->objectListEditor->onChange(function ($value) {
            $this->input->text = $value;
        });

        $this->typeSelect = new UXComboBox();
        $this->typeSelect->editable = false;

        foreach ($this->valueTypes() as $code) {
            $this->typeSelect->items->add($this->valueTypeLabels()[$code] ?: $code);
        }

        $this->typeSelect->width = 120;

        $this->input = new UXTextField();
        $this->input->maxWidth = 9999;

        $this->box = new UXHBox([$this->typeSelect, $this->input]);
        $this->box->spacing = 5;
        $this->box->maxWidth = 99999;

        UXHBox::setHgrow($this->input, 'ALWAYS');
        UXHBox::setHgrow($this->objectListEditor->getUi(), 'ALWAYS');
        UXHBox::setHgrow($this->formListEditor->getUi(), 'ALWAYS');

        $this->typeSelect->on('action', function () {
            $this->updateUi();
        });

        return $this->box;
    }

    public function updateUi()
    {
        $this->box->children->clear();
        $this->box->add($this->typeSelect);

        if ($this->typeSelect->selected == $this->valueTypeLabels()['object']) {
            $this->box->add($this->objectListEditor->getUi());
            $this->objectListEditor->getUi()->requestFocus();

            if (!$this->value) {
                $this->objectListEditor->setSelected(null);
            }
        } elseif ($this->typeSelect->selected == $this->valueTypeLabels()['form']) {
            $this->box->add($this->formListEditor->getUi());
            $this->formListEditor->getUi()->requestFocus();

            if (!$this->value) {
                $this->formListEditor->setSelected(null);
            }
        } else {
            $this->box->add($this->input);
            $this->input->requestFocus();
        }
    }

    public function setValue($value, $type)
    {
        parent::setValue($value, $type);

        $this->input->text = $value;

        foreach ($this->valueTypes() as $i => $el) {
            if ($el == $type) {
                $this->typeSelect->selectedIndex = $i;
                break;
            }
        }

        if ($this->typeSelect->selectedIndex < 0) {
            $this->typeSelect->selectedIndex = 0;
        }

        if ($type == 'object') {
            $this->objectListEditor->setSelected($value);
        }

        $this->formListEditor->setSelected($value);

        $this->updateUi();
    }

    public function getValue()
    {
        return $this->input->text;
    }

    public function getValueType()
    {
        return $this->valueTypes()[$this->typeSelect->selectedIndex] ?: "string";
    }

    public function requestUiFocus()
    {
        $this->input->requestFocus();
    }
}