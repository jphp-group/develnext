<?php
namespace ide\formats;

use ide\editors\AbstractEditor;
use ide\editors\CodeEditor;
use ide\utils\FileUtils;
use php\gui\designer\UXSyntaxAutoCompletion;
use php\lib\fs;
use php\lib\Str;

class FxCssCodeFormat extends AbstractFormat
{
    /**
     * @param $file
     *
     * @param array $options
     * @return AbstractEditor
     */
    public function createEditor($file, array $options = [])
    {
        $editor = new CodeEditor($file, 'fxcss');
        return $editor;
    }


    /**
     * @param $file
     *
     * @return bool
     */
    public function isValid($file)
    {
        return str::endsWith($file, '.fx.css');
    }

    public function getIcon()
    {
        return 'icons/cssFile16.png';
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