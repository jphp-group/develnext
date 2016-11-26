<?php
namespace php\gui;

/**
 * Class UXRating
 * @package php\gui
 */
class UXRating extends UXControl
{
    /**
     * @var int
     */
    public $max;

    /**
     * @var int
     */
    public $value;

    /**
     * @var string
     */
    public $orientation = 'HORIZONTAL';

    /**
     * @var bool
     */
    public $partialRating = false;

    /**
     * @var bool
     */
    public $updateOnHover = false;
}