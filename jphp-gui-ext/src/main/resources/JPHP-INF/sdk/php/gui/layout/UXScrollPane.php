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
     * @param UXNode $node (optional)
     */
    public function __construct(UXNode $node)
    {
    }
}