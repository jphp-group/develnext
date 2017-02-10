<?php
namespace ide\formats;

use ide\editors\AbstractEditor;
use ide\editors\CodeEditor;
use ide\utils\FileUtils;
use php\gui\designer\UXSyntaxAutoCompletion;
use php\lib\Str;

class CssCodeFormat extends AbstractFormat
{
    /**
     * @param $file
     *
     * @param array $options
     * @return AbstractEditor
     */
    public function createEditor($file, array $options = [])
    {
        $editor = new CodeEditor($file, 'css');
        $editor->registerDefaultCommands();

        return $editor;
    }

    public function getTitle($path)
    {
        if (Str::endsWith(FileUtils::normalizeName($path), ".theme/style.css")) {
            return "CSS Стиль";
        }

        return parent::getTitle($path);
    }


    /**
     * @param $file
     *
     * @return bool
     */
    public function isValid($file)
    {
        return Str::endsWith($file, '.css');
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