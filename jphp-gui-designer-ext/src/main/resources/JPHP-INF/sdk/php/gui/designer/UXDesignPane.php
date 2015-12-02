<?php
namespace php\gui\designer;

use php\gui\layout\UXAnchorPane;

/**
 * Class UXDesignPane
 * @package php\gui\designer
 */
class UXDesignPane extends UXAnchorPane
{
    /**
     * @var int
     */
    public $borderWidth = 8;

    /**
     * @var int
     */
    public $snapSize = 8;

    /**
     * @var string
     */
    public $borderColor = 'gray';

    /**
     * @readonly
     * @var bool
     */
    public $editing;

    /**
     * @param callable|null $handle
     */
    public function onResize(callable $handle)
    {
    }
}