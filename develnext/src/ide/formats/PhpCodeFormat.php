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
            'lineNumbers'         => true,
            'continueComments'    => true,
            'styleActiveLine'     => true,
            'selectionPointer'    => true,

            'theme'               => 'ambiance',
            'mode'                => 'application/x-httpd-php',
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