<?php
namespace php\gui\designer;


use php\gui\text\UXFont;
use php\gui\UXNode;

/**
 * Class UXSyntaxTextArea
 * @package php\gui\designer
 */
class UXSyntaxTextArea extends UXNode
{
    /**
     * @var string
     */
    public $text;

    /**
     * @var bool
     */
    public $editable = true;

    /**
     * @var string
     */
    public $syntaxStyle;

    /**
     * @var UXFont
     */
    public $font;

    /**
     * @param $line
     * @param $pos
     */
    public function jumpToLine($line, $pos)
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

    public function showFindDialog()
    {
    }

    public function showReplaceDialog()
    {
    }
}