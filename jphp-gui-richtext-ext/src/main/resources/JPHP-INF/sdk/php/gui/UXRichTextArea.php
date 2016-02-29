<?php
namespace php\gui;

/**
 * Class UXRichTextArea
 * @package php\gui
 */
class UXRichTextArea extends UXControl
{
    /**
     * @readonly
     * @var string
     */
    public $text;

    /**
     * @var string
     */
    public $selectedText;

    /**
     * @var UXPopupWindow
     */
    public $popupWindow;

    /**
     * @var bool
     */
    public $wrapText = false;

    /**
     * @var int
     */
    public $caretPosition;

    /**
     * @var bool
     */
    public $useInitialStyleForInsertion = false;

    /**
     *
     */
    public function clear()
    {
    }

    /**
     *
     */
    public function selectAll()
    {
    }

    /**
     *
     */
    public function selectLine()
    {
    }

    /**
     * @param int $anchor
     * @param int $caretPos
     */
    public function selectRange($anchor, $caretPos)
    {
    }

    /**
     * @return string
     */
    public function getSelectedText()
    {
    }

    /**
     * @return array [start, end, length]
     */
    public function getSelection()
    {
    }

    /**
     * @param string $text
     * @param null|string $style
     */
    public function appendText($text, $style = null)
    {
    }

    /**
     * @param int $pos
     * @param string $text
     */
    public function insertText($pos, $text)
    {
    }

    /**
     * @param int $from
     * @param int $to
     * @param string $style
     */
    public function setStyle($from, $to, $style)
    {
    }

    /**
     * @param int $paragraph
     * @param string $style
     */
    public function setStyleOfParagraph($paragraph, $style)
    {
    }

    /**
     * @param int $position
     */
    public function getStyleAtPosition($position)
    {
    }

    /**
     * @param int $paragraph
     * @param int $offset
     */
    public function getStyleAtParagraph($paragraph, $offset)
    {
    }

    /**
     * @param int $from
     * @param int $to
     */
    public function clearStyle($from, $to)
    {
    }

    /**
     * @param int $paragraph
     */
    public function clearStyleOfParagraph($paragraph)
    {
    }
}