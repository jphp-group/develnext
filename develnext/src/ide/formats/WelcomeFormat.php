<?php
namespace ide\formats;

use ide\editors\AbstractEditor;
use ide\editors\WelcomeEditor;

/**
 * Class WelcomeFormat
 * @package ide\formats
 */
class WelcomeFormat extends AbstractFormat
{
    /**
     * @param $file
     *
     * @param array $options
     * @return AbstractEditor
     */
    public function createEditor($file, array $options = [])
    {
        return new WelcomeEditor($file);
    }

    /**
     * @param $file
     *
     * @return bool
     */
    public function isValid($file)
    {
        return $file == '~welcome';
    }

    /**
     * @param $any
     *
     * @return mixed
     */
    public function register($any)
    {

    }
}