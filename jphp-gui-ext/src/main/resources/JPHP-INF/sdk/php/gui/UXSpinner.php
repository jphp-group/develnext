<?php
namespace php\gui;

/**
 * Class UXSpinner
 * @package php\gui
 * @packages gui, javafx
 */
class UXSpinner extends UXControl
{
    /**
     * @var bool
     */
    public $editable = true;

    /**
     * @readonly
     * @var UXTextField
     */
    public $editor;

    /**
     * @param callable|null $incrementHandler
     * @param callable|null $decrementHandler
     */
    public function setValueFactory($incrementHandler, $decrementHandler)
    {
    }

    /**
     * @param int $min (optional)
     * @param int $max (optional)
     * @param int $initial (optional)
     * @param int $step
     */
    public function setIntegerValueFactory($min, $max, $initial, $step = 1)
    {
    }
}