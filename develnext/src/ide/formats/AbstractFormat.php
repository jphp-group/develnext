<?php
namespace ide\formats;

use ide\editors\AbstractEditor;

/**
 * Class AbstractFormat
 * @package ide\formats
 */
abstract class AbstractFormat
{
    /**
     * @param $file
     * @return AbstractEditor
     */
    abstract public function createEditor($file);

    /**
     * @param $file
     * @return bool
     */
    abstract public function isValid($file);

    /**
     * @param $any
     * @return mixed
     */
    abstract public function register($any);
}