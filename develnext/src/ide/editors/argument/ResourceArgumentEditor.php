<?php
namespace ide\editors\argument;

use ide\editors\common\FileListEditor;
use ide\Ide;
use php\gui\layout\UXVBox;
use php\gui\UXNode;
use php\lib\str;

abstract class ResourceArgumentEditor extends AbstractArgumentEditor
{
    /**
     * @var FileListEditor
     */
    protected $ui;

    /**
     * @return string
     */
    abstract public function getPath();

    /**
     * @return string
     */
    abstract public function getIcon();

    /**
     * @return array
     */
    abstract public function getExtensions();

    /**
     * @return string
     */
    abstract public function getExtensionDescription();

    /**
     * @return string
     */
    public function getPrefixValue()
    {
        return "res://";
    }

    /**
     * @return bool
     */
    public function isRecursive()
    {
        return true;
    }

    /**
     * @param null $label
     * @return UXNode
     */
    public function makeUi($label = null)
    {
        $this->ui = new FileListEditor(
            Ide::project()->getFile($this->getPath()),
            $this->getExtensionDescription(),
            $this->getExtensions(),
            $this->isRecursive()
        );
        $this->ui->setIcon($this->getIcon());

        $this->ui->maxWidth = 9999;
        UXVBox::setVgrow($this->ui, 'ALWAYS');

        return $this->ui;
    }

    /**
     * @return mixed
     */
    public function updateUi()
    {
        $this->ui->update();
    }

    /**
     * @param $value
     * @param $type
     */
    public function setValue($value, $type)
    {
        parent::setValue($value, $type);

        if (str::startsWith($value, $this->getPrefixValue())) {
            $value = str::sub($value, str::length($this->getPrefixValue()));
        }

        $this->ui->setRelativeValue($type == 'string' ? $value : null);
    }

    /**
     * @return string
     */
    public function getValue()
    {
        $value = $this->ui->getRelativeValue();
        return $value ? $this->getPrefixValue() . $value : 'null';
    }

    /**
     * @return string
     */
    public function getValueType()
    {
        return $this->ui->getValue() == null ? 'expr' : 'string';
    }

    public function requestUiFocus()
    {
        $this->ui->requestFocus();
    }
}