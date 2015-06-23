<?php
namespace ide\editors\value;

use php\gui\designer\UXDesignPropertyEditor;
use php\gui\framework\DataUtils;
use php\gui\UXNode;
use php\gui\UXTableCell;

/**
 * Class ElementPropertyEditor
 * @package ide\editors\value
 */
abstract class ElementPropertyEditor extends UXDesignPropertyEditor
{
    /**
     * @var UXNode
     */
    protected $content;

    /**
     * @var callable
     */
    protected $getter;

    /**
     * @var callable
     */
    protected $setter;

    /**
     * ElementPropertyEditor constructor.
     *
     * @param callable $getter
     * @param callable $setter
     */
    public function __construct(callable $getter = null, callable $setter = null)
    {
        $this->getter = $getter;
        $this->setter = $setter;

        $this->content = $this->makeUi();
    }


    abstract public function makeUi();

    /**
     * @param $value
     */
    abstract public function updateUi($value);

    /**
     * @param $value
     *
     * @return mixed
     */
    public function getNormalizedValue($value)
    {
        return $value;
    }

    /**
     * @param UXTableCell $cell
     * @param bool $empty
     *
     * @return mixed
     */
    public function update(UXTableCell $cell, $empty)
    {
        $cell->graphic = $this->content;
        $this->updateUi($this->getNormalizedValue($this->getValue()));
    }

    /**
     * @return $this
     */
    public function setAsDataProperty()
    {
        $this->setter = function (ElementPropertyEditor $editor, $value) {
            $target = $this->designProperties->target;

            if ($target->id) {
                $data = DataUtils::get($target);
                $data->set($editor->code, $value);
            }
        };

        $this->getter = function (ElementPropertyEditor $editor) {
            $target = $this->designProperties->target;

            if ($target->id) {
                $data = DataUtils::get($target);

                return $data->get($editor->code);
            }

            return '';
        };

        return $this;
    }

    public function applyValue($value, $updateUi = true)
    {
        $value = $this->getNormalizedValue($value);

        if (!$this->setter) {
            $this->designProperties->target->{$this->code} = $value;
        } else {
            $setter = $this->setter;
            $setter($this, $value);
        }

        if ($updateUi) {
            $this->updateUi($value);
        }
    }

    public function getValue()
    {
        if (!$this->getter) {
            $value = $this->designProperties->target->{$this->code};
            return $value;
        } else {
            $getter = $this->getter;
            return $getter($this);
        }
    }
}