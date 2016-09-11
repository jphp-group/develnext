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
    protected $instancesListEditor;

    /** @var ObjectListEditor */
    protected $formListEditor;

    /**
     * @return ObjectListEditor
     */
    public function getObjectListEditor()
    {
        return $this->objectListEditor;
    }

    /**
     * @return ObjectListEditor
     */
    public function getFormListEditor()
    {
        return $this->formListEditor;
    }


    public function getCode()
    {
        return "mixed";
    }

    protected function valueTypes()
    {
        return ['string', 'magicString', 'integer', 'float', 'object', 'instances', 'form', 'variable', 'expr', 'score'];
    }

    protected function valueTypeLabels()
    {
        return [
            'string' => 'Строка',
            'magicString' => 'Строка с переменными',
            'object' => 'Объект',
            'instances' => 'Объекты (клоны)',
            'form' => 'Форма',
            'globalVariable' => 'Глобальная переменная',
            'variable' => 'Переменная',
            'expr' => 'PHP код',
            'integer' => 'Число',
            'float' => 'Дробное число',
            'boolean' => 'Логика',
            'score' => 'Счет',
        ];
    }

    protected function makeObjectListUi()
    {
        $this->objectListEditor = new ObjectListEditor();

        if ($filter = $this->options['objectFilter']) {
            $this->objectListEditor->addFilter($filter);
        }

        $this->objectListEditor->enableSender();

        //if (!($this->userData instanceof ScriptModuleEditor)) {
            $this->objectListEditor->enableAllForms();
        //}

        if ($this->options['objectDisableForms']) {
            $this->objectListEditor->disableForms();
        }

        if ($value = $this->options['formMethod']) {
            $this->objectListEditor->formMethod($value);
        }

        $this->objectListEditor->build();

        $this->objectListEditor->getUi()->maxWidth = 9999;
        $this->objectListEditor->onChange(function ($value) {
            $this->input->text = $value;
        });

        UXHBox::setHgrow($this->objectListEditor->getUi(), 'ALWAYS');
    }

    protected function makeFormListUi()
    {
        $this->formListEditor = new FormListEditor();
        $this->formListEditor->enableSender();
        $this->formListEditor->build();

        $this->formListEditor->getUi()->maxWidth = 9999;
        $this->formListEditor->onChange(function ($value) {
            $this->input->text = $value;
        });


        UXHBox::setHgrow($this->formListEditor->getUi(), 'ALWAYS');
    }

    protected function makeInstancesListUi()
    {
        $this->instancesListEditor = new ObjectListEditor();

        if ($filter = $this->options['objectFilter']) {
            $this->instancesListEditor->addFilter($filter);
        }

        $this->instancesListEditor->disableDependencies();
        $this->instancesListEditor->enableAllForms();
        $this->instancesListEditor->stringValues();
        $this->instancesListEditor->build();

        $this->instancesListEditor->getUi()->maxWidth = 9999;
        $this->instancesListEditor->onChange(function ($value) {
            $this->input->text = $value;
        });

        UXHBox::setHgrow($this->instancesListEditor->getUi(), 'ALWAYS');
    }

    /**
     * @param null $label
     * @return UXNode
     */
    public function makeUi($label = null)
    {
        if (in_array('form', $this->valueTypes())) {
            $this->makeFormListUi();
        }

        if (in_array('object', $this->valueTypes())) {
            $this->makeObjectListUi();
        }

        if (in_array('instances', $this->valueTypes())) {
            $this->makeInstancesListUi();
        }

        $this->typeSelect = new UXComboBox();
        $this->typeSelect->editable = false;

        foreach ($this->valueTypes() as $code) {
            $this->typeSelect->items->add($this->valueTypeLabels()[$code] ?: $code);
        }

        $this->typeSelect->width = 135;

        $this->input = new UXTextField();
        $this->input->maxWidth = 9999;

        $this->box = new UXHBox([$this->typeSelect, $this->input]);
        $this->box->spacing = 5;
        $this->box->maxWidth = 99999;

        UXHBox::setHgrow($this->input, 'ALWAYS');

        $this->typeSelect->on('action', function () {
            $this->update();
        });

        return $this->box;
    }

    public function updateUi()
    {
        if ($this->objectListEditor) {
            $this->objectListEditor->updateUi();
        }

        if ($this->formListEditor) {
            $this->formListEditor->updateUi();
        }

        if ($this->instancesListEditor) {
            $this->instancesListEditor->updateUi();
        }

        //$this->update();
    }

    public function update()
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
        } elseif ($this->typeSelect->selected == $this->valueTypeLabels()['instances']) {
            $this->box->add($this->instancesListEditor->getUi());
            $this->instancesListEditor->getUi()->requestFocus();

            if (!$this->value) {
                $this->instancesListEditor->setSelected(null);
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

        if ($type == 'object' && $this->objectListEditor) {
            $this->objectListEditor->setSelected($value);
        }

        if ($this->instancesListEditor) {
            $this->instancesListEditor->setSelected($value);
        }

        if ($this->formListEditor) {
            $this->formListEditor->setSelected($value);
        }

        $this->update();
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