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
     * @var double
     */
    public $scrollX;

    /**
     * @var double
     */
    public $scrollY;

    /**
     * @var bool
     */
    public $fitToWidth = false;

    /**
     * @var bool
     */
    public $fitToHeight = false;

    /**
     * @var string AS_NEEDED, ALWAYS, NEVER
     */
    public $vbarPolicy = 'AS_NEEDED';

    /**
     * @var string AS_NEEDED, ALWAYS, NEVER
     */
    public $hbarPolicy = 'AS_NEEDED';

    /**
     * @param UXNode $node (optional)
     */
    public function __construct(UXNode $node)
    {
    }
}