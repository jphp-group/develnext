<?php
namespace php\gui\designer;

use php\gui\UXControl;

class UXAbstractCodeArea extends UXControl
{
    /**
     * @var string
     */
    public $text;

    /**
     * @var string
     */
    public $selectedText;

    /**
     * @var bool
     */
    public $editable = true;

    /**
     * @var int
     */
    public $caretPosition;

    /**
     * @readonly
     * @var int
     */
    public $caretOffset;

    /**
     * @readonly
     * @var int
     */
    public $caretLine;

    /**
     * @param $line
     * @param $pos
     */
    public function jumpToLine($line, $pos)
    {
    }

    /**
     * @param $text
     */
    public function insertToCaret($text)
    {
    }

    public function undo()
    {
    }

    public function redo()
    {
    }

    public function copy()
    {
    }

    public function paste()
    {
    }

    public function cut()
    {
    }

    /**
     * @param int $start
     * @param int $end
     */
    public function select($start, $end)
    {
    }
}