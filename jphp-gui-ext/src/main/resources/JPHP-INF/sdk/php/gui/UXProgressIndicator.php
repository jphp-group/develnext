<?php
namespace php\gui;

/**
 * Class ProgressIndicator
 * @package php\gui
 */
class UXProgressIndicator extends UXControl
{
    /**
     * @var double
     */
    public $progress;

    /**
     * @readonly
     * @var bool
     */
    public $indeterminate;
}