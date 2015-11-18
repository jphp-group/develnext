<?php
namespace ide\formats;

use ide\editors\AbstractEditor;
use ide\editors\CodeEditor;
use php\gui\designer\UXSyntaxAutoCompletion;
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
        $editor = new CodeEditor($file, 'php');

        $completion = new UXSyntaxAutoCompletion();
        $completion->addVariableCompletion('$this', 'object');
        $completion->addVariableCompletion('$event', 'mixed');

      //  $editor->installAutoCompletion($completion);

        return $editor;
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