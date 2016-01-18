<?php
namespace ide\editors\argument;

use ide\editors\common\ObjectListEditor;
use php\gui\UXNode;

class PrototypeArgumentEditor extends AbstractArgumentEditor
{
    /** @var ObjectListEditor */
    protected $instancesListEditor;

    protected function makeInstancesListUi()
    {
        $this->instancesListEditor = new ObjectListEditor();

        if ($filter = $this->options['objectFilter']) {
            $this->instancesListEditor->addFilter($filter);
        }

        $this->instancesListEditor->stringValues();
        $this->instancesListEditor->disableModules();
        $this->instancesListEditor->enableAllForms();
        $this->instancesListEditor->build();

        $this->instancesListEditor->getUi()->maxWidth = 9999;
    }

    public function getCode()
    {
        return "prototype";
    }

    /**
     * @param null $label
     * @return UXNode
     */
    public function makeUi($label = null)
    {
        $this->makeInstancesListUi();

        return $this->instancesListEditor->getUi();
    }

    public function requestUiFocus()
    {
        $this->instancesListEditor->getUi()->requestFocus();
    }

    public function getValueType()
    {
        return 'string';
    }

    public function getValue()
    {
        return $this->instancesListEditor->getSelected();
    }

    public function setValue($value, $type)
    {
        parent::setValue($value, $type);

        $this->instancesListEditor->setSelected($value);
    }
}