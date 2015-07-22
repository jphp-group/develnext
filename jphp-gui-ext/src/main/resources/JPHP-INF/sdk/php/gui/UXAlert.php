<?php
namespace php\gui;

/**
 * Class UXAlert
 * @package php\gui
 */
class UXAlert
{
    /** @var string */
    public $contentText = '';

    /** @var string */
    public $headerText = '';

    /** @var string */
    public $title = '';

    /** @var bool */
    public $expanded = true;

    /** @var UXNode */
    public $graphic = null;

    /** @var UXNode */
    public $expandableContent = null;

    /**
     * @param $alertType
     **/
    public function __construct($alertType)
    {
    }

    /**
     * @param array $buttons
     */
    public function setButtonTypes(array $buttons)
    {
    }

    /**
     * ...
     */
    public function show()
    {
    }


    /**
     * ...
     */
    public function hide()
    {
    }

    /**
     * @return string|null
     */
    public function showAndWait()
    {
    }
}