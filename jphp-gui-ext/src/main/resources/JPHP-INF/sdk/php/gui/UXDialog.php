<?php
namespace php\gui;

/**
 * Class UXDialog
 * @package php\gui
 */
class UXDialog
{
    /**
     * @param $text
     * @param string $type
     * @return null|string
     */
    public static function show($text, $type = 'NONE')
    {
    }

    /**
     * @param $text
     * @param UXNode $content
     * @param $expanded
     * @param $type
     */
    public static function showExpanded($text, UXNode $content, $expanded, $type = 'NONE')
    {
    }

    /**
     * @param $text
     * @return bool
     */
    public static function confirm($text)
    {
    }

    /**
     * @param $text
     * @param $default
     * @return string|null
     */
    public static function input($text, $default = '')
    {
    }
}