<?php
namespace ide\autocomplete;

/**
 * Class AutoCompleteRegion
 * @package ide\autocomplete
 */
class AutoCompleteRegion
{
    protected $fromLine, $toLine = -1;
    protected $fromPos, $toPos = -1;

    /**
     * @var array
     */
    protected $values = [];

    /**
     * AutoCompleteRegion constructor.
     * @param $fromLine
     * @param $fromPos
     */
    public function __construct($fromLine, $fromPos)
    {
        $this->fromLine = $fromLine;
        $this->fromPos = $fromPos;
    }

    /**
     * @return mixed
     */
    public function getFromLine()
    {
        return $this->fromLine;
    }

    /**
     * @param mixed $fromLine
     */
    public function setFromLine($fromLine)
    {
        $this->fromLine = $fromLine;
    }

    /**
     * @return int
     */
    public function getToLine()
    {
        return $this->toLine;
    }

    /**
     * @param int $toLine
     */
    public function setToLine($toLine)
    {
        $this->toLine = $toLine;
    }

    /**
     * @return mixed
     */
    public function getFromPos()
    {
        return $this->fromPos;
    }

    /**
     * @param mixed $fromPos
     */
    public function setFromPos($fromPos)
    {
        $this->fromPos = $fromPos;
    }

    /**
     * @return int
     */
    public function getToPos()
    {
        return $this->toPos;
    }

    /**
     * @param int $toPos
     */
    public function setToPos($toPos)
    {
        $this->toPos = $toPos;
    }

    public function isAcross($line, $pos)
    {
        return $line >= $this->fromLine && ($line <= $this->toLine || $this->toLine == -1);
            //&& $pos >= $this->fromPos && ($pos <= $this->toPos || $this->toPos == -1);
    }

    public function setValue($value, $category)
    {
        $this->values[$category][] = $value;
    }


    public function setValueAsRef(&$value, $category)
    {
        $this->values[$category][] =& $value;
    }

    public function getValues($category)
    {
        return (array) $this->values[$category];
    }

    public function &getLastValue($category)
    {
        $values = $this->getValues($category);

        return $values[sizeof($values) - 1];
    }
}