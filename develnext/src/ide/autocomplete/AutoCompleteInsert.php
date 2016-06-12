<?php
namespace ide\autocomplete;

use php\gui\designer\UXAbstractCodeArea;

/**
 * Class AutoCompleteInserter
 * @package ide\autocomplete
 */
class AutoCompleteInsert
{
    /**
     * @var UXAbstractCodeArea
     */
    protected $area;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    protected $beforeText;

    /**
     * @var string
     */
    protected $afterText;

    /**
     * AutoCompleteInsert constructor.
     * @param UXAbstractCodeArea $area
     */
    public function __construct(UXAbstractCodeArea $area)
    {
        $this->area = $area;
    }

    /**
     * @return UXAbstractCodeArea
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getBeforeText()
    {
        return $this->beforeText;
    }

    /**
     * @param string $beforeText
     */
    public function setBeforeText($beforeText)
    {
        $this->beforeText = $beforeText;
    }

    /**
     * @return string
     */
    public function getAfterText()
    {
        return $this->afterText;
    }

    /**
     * @param string $afterText
     */
    public function setAfterText($afterText)
    {
        $this->afterText = $afterText;
    }
}