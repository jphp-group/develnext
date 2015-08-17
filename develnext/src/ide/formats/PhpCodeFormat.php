<?php
namespace ide\formats;

use ide\editors\AbstractEditor;
use ide\editors\CodeEditor;
use php\lib\Str;

/**
 * Class PhpCodeFormat
 * @package ide\formats
 */
class PhpCodeFormat extends AbstractFormat
{
    /**
     * @param $file
     *
     * @return AbstractEditor
     */
    public function createEditor($file)
    {
        return new CodeEditor($file, 'php', [
            'enableBasicAutocompletion' => true,
            'enableSnippets' => true,
            'enableLiveAutocompletion' => true,
            'showPrintMargin' => false,
            'fontSize' => 14,

            'theme' => 'ambiance',
        ]);
    }

    /**
     * @param $file
     *
     * @return bool
     */
    public function isValid($file)
    {
        return Str::endsWith($file, '.php');
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