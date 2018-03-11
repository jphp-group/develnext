<?php
namespace ide\jsplatform\formats;

use ide\editors\AbstractEditor;
use ide\editors\CodeEditor;
use ide\formats\AbstractFormat;
use php\lib\fs;

class JavaScriptCodeFormat extends AbstractFormat
{
    /**
     * @param $file
     * @param array $options
     * @return AbstractEditor
     */
    public function createEditor($file, array $options = [])
    {
        $editor = new CodeEditor($file, 'js');

        return $editor;
    }

    /**
     * @param $file
     * @return bool
     */
    public function isValid($file)
    {
        return fs::ext($file) == 'js';
    }

    /**
     * @param $any
     * @return mixed
     */
    public function register($any)
    {

    }
}