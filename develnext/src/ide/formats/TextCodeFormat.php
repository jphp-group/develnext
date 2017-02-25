<?php
namespace ide\formats;

use ide\editors\AbstractEditor;
use ide\editors\CodeEditor;
use ide\utils\FileUtils;
use php\gui\designer\UXSyntaxAutoCompletion;
use php\lib\fs;
use php\lib\Str;

class TextCodeFormat extends AbstractFormat
{
    private $exts = [
        'txt' => 1, 'log' => 1, 'ini' => 1, 'conf' => 1, 'gitignore' => 1, 'csv' => 1, 'html' => 1, 'xml' => 1, '' => 1,
        'bat' => 1, 'sh' => 1
    ];

    /**
     * @param $file
     *
     * @param array $options
     * @return AbstractEditor
     */
    public function createEditor($file, array $options = [])
    {
        $editor = new CodeEditor($file, 'text');

        return $editor;
    }

    /**
     * @param $file
     *
     * @return bool
     */
    public function isValid($file)
    {
        return (bool) $this->exts[fs::ext($file)];
    }

    public function getIcon()
    {
        return 'icons/textFile16.png';
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