<?php
namespace php\gui\designer;

use php\gui\UXControl;
use php\gui\UXPopupWindow;

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
     * @readonly
     * @var double
     */
    public $lineHeight;

    /**
     * @var float
     */
    public $estimatedScrollY = 0.0;

    /**
     * @var float
     */
    public $estimatedScrollX = 0.0;

    /**
     * @var UXPopupWindow
     */
    public $popupWindow;

    /**
     * @param $line
     * @param $pos
     */
    public function jumpToLine($line, $pos)
    {
    }

    /**
     * @param $line
     */
    public function jumpToLineSpaceOffset($line)
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

    public function showPopup()
    {
    }

    public function hidePopup()
    {
    }

    public function forgetHistory()
    {
    }

    /**
     * @param int $from
     * @param int $to
     */
    public function deleteText($from, $to)
    {
    }

    /**
     * @param int $index
     * @param int $text
     */
    public function insertText($index, $text)
    {
    }

    /**
     * @param int $from
     * @param int $to
     * @param string $text
     */
    public function replaceText($from, $to, $text)
    {
    }
}