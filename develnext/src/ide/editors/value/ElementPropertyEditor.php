<?php
namespace ide\editors\value;

use php\gui\designer\UXDesignPropertyEditor;
use php\gui\framework\DataUtils;
use php\gui\UXNode;
use php\gui\UXTableCell;
use php\xml\DomElement;

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
     * @var string
     */
    protected $tooltip;

    /**
     * @var callable
     */
    protected $getter;

    /**
     * @var callable
     */
    protected $setter;

    /**
     * @var ElementPropertyEditor[]
     */
    protected static $editors = [];

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

    /**
     * @return string
     */
    abstract public function getCode();

    /**
     * @return UXNode
     */
    abstract public function makeUi();

    /**
     * @param $value
     */
    abstract public function updateUi($value);

    /**
     * @param DomElement $element
     *
     * @return ElementPropertyEditor
     */
    abstract public function unserialize(DomElement $element);

    /**
     * @param string $tooltip
     */
    public function setTooltip($tooltip)
    {
        $this->tooltip = $tooltip;
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public function getNormalizedValue($value)
    {
        return $value;
    }

    public function getCssNormalizedValue($value)
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

    /**
     * @return $this
     */
    public function setAsCssProperty()
    {
        $this->setter = function (ElementPropertyEditor $editor, $value) {
            $target = $this->designProperties->target;
            $target->css($editor->code, $editor->getCssNormalizedValue($value));
        };

        $this->getter = function (ElementPropertyEditor $editor) {
            $target = $this->designProperties->target;
            return $target->css($editor->code);
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

    /**
     * @param ElementPropertyEditor $editor
     */
    public static function register(ElementPropertyEditor $editor)
    {
        static::$editors[$editor->getCode()] = $editor;
    }

    /**
     * @param $code
     *
     * @return ElementPropertyEditor
     * @throws \Exception
     */
    public static function getByCode($code)
    {
        $editor = static::$editors[$code];

        if (!$editor) {
            throw new \Exception("Unable to find the '$code' editor");
        }

        return $editor;
    }
}