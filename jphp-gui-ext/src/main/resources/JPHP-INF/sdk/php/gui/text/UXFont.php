<?php
namespace php\gui\text;

use php\io\Stream;

/**
 * Class UXFont
 * @package php\gui\text
 */
class UXFont
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $family;

    /**
     * @var int
     */
    public $size;

    /**
     * @var string
     */
    public $style;

    /**
     * @param double $size
     * @param string $family (optional)
     */
    public function __construct($size, $family)
    {
    }

    /**
     * @param string $family
     * @param double $size
     *
     * @return UXFont
     */
    public static function of($family, $size)
    {
    }

    /**
     * @param Stream $stream
     * @param double $size
     *
     * @return UXFont
     */
    public static function load(Stream $stream, $size)
    {
    }

    /**
     * @return UXFont
     */
    public static function getDefault()
    {
    }

    /**
     * @param string $family (optional)
     *
     * @return string[]
     */
    public static function getFontNames($family)
    {
    }

    /**
     * @return string[]
     */
    public static function getFamilies()
    {
    }
}