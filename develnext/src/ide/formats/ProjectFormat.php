<?php
namespace ide\formats;

use ide\editors\AbstractEditor;
use ide\editors\ProjectEditor;
use ide\editors\WelcomeEditor;

/**
 * @package ide\formats
 */
class ProjectFormat extends AbstractFormat
{
    /**
     * @param $file
     *
     * @return AbstractEditor
     */
    public function createEditor($file)
    {
        return new ProjectEditor($file);
    }

    public function getIcon()
    {
        return 'icons/myProject16.png';
    }

    /**
     * @param $file
     *
     * @return bool
     */
    public function isValid($file)
    {
        return $file == '~project';
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