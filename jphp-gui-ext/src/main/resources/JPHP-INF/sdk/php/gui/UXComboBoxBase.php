<?php
namespace php\gui;

/**
 * Class UXComboBoxBase
 * @package php\gui
 *
 * @method show()
 * @method hide()
 * @method arm()
 * @method disarm()
 */
class UXComboBoxBase extends UXControl
{
    /**
     * @var bool
     */
    public $armed;

    /**
     * Редактируемый.
     * @var bool
     */
    public $editable;

    /**
     * Текст-подсказка.
     * @var string
     */
    public $promptText;

    /**
     * @readonly
     * @var bool
     */
    public $showing;

    /**
     * Значение.
     * @var mixed
     */
    public $value;

    /**
     * Текст.
     * @var string
     */
    public $text;

    /**
     * Показать меню-попап.
     */
    public function showPopup()
    {
    }
}