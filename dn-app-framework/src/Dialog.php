<?php

use php\gui\UXDialog;

/**
 * Class Dialog
 *
 * @packages framework
 */
class Dialog
{
    /**
     * @param string $text
     * @param string $type
     *
     * @return null|string
     */
    static function show($text, $type = 'INFORMATION')
    {
        return UXDialog::show($text, $type);
    }

    /**
     * @param $text
     * @param string $type
     *
     * @return null|string
     */
    static function message($text, $type = 'INFORMATION')
    {
        return static::show($text, $type);
    }

    /**
     * @param $text
     * @param string $type
     *
     * @return null|string
     */
    static function alert($text, $type = 'INFORMATION')
    {
        return static::show($text, $type);
    }

    /**
     * @param $text
     *
     * @return null|string
     */
    static function error($text)
    {
        return static::show($text, 'ERROR');
    }

    /**
     * @param $text
     *
     * @return null|string
     */
    static function warning($text)
    {
        return static::show($text, 'WARNING');
    }

    /**
     * @param $text
     *
     * @return bool
     */
    static function confirm($text)
    {
        return UXDialog::confirm($text);
    }

    /**
     * @param $text
     * @param string $default
     *
     * @return null|string
     */
    static function input($text, $default = '')
    {
        return UXDialog::input($text, $default);
    }
}