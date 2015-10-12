<?php
namespace php\gui\designer;

/**
 * Class UXSyntaxAutoCompletion
 * @package php\gui\designer
 */
class UXSyntaxAutoCompletion
{
    /**
     * @var int
     */
    public $autoActivationDelay = 200;

    /**
     * @var bool
     */
    public $autoActivationEnabled = true;

    /**
     * @var bool
     */
    public $autoCompleteEnabled = true;

    /**
     * ...
     */
    public function doCompletion()
    {
    }

    /**
     * @param UXSyntaxTextArea $area
     */
    public function install(UXSyntaxTextArea $area)
    {
    }

    /**
     * ...
     */
    public function uninstall()
    {
    }

    /**
     * @param string $text
     * @param string $desc
     */
    public function addCompletion($text, $desc)
    {
    }

    /**
     * @param string $text
     * @param string $replacement
     * @param string $desc
     */
    public function addShorthandCompletion($text, $replacement, $desc)
    {
    }

    /**
     * @param string $name
     * @param string $type
     */
    public function addVariableCompletion($name, $type)
    {
    }


    /**
     *
     */
    public function clearCompletions()
    {
    }

}