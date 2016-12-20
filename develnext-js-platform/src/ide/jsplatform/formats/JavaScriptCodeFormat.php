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
     * @return AbstractEditor
     */
    public function createEditor($file)
    {
        return new CodeEditor($file, 'js');
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