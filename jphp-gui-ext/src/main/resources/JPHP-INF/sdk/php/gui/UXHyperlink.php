<?php
namespace php\gui;

/**
 * Class UXHyperlink
 * @package php\gui
 *
 * @packages gui, javafx
 */
class UXHyperlink extends UXButtonBase
{


    /**
     * @var bool
     */
    public $visited = false;

    /**
     * @param string $text (optional)
     * @param UXNode $graphic (optional)
     */
    public function __construct($text, UXNode $graphic)
    {
    }
}