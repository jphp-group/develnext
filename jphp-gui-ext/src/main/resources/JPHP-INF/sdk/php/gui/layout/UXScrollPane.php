<?php
namespace php\gui\layout;

use php\gui\UXControl;
use php\gui\UXNode;

/**
 * Class UXScrollPane
 * @package php\gui\layout
 */
class UXScrollPane extends UXControl
{
    /**
     * @var UXNode
     */
    public $content;

    /**
     * @var bool
     */
    public $fitToWidth = false;

    /**
     * @var bool
     */
    public $fitToHeight = false;

    /**
     * @param UXNode $node (optional)
     */
    public function __construct(UXNode $node)
    {
    }
}