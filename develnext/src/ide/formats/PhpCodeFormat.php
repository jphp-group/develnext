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
     * @param array $options
     * @return AbstractEditor
     */
    public function createEditor($file, array $options = [])
    {
        $editor = new CodeEditor($file, 'php');

        if (!$options['withoutCommands']) {
            $editor->registerDefaultCommands();
        }

        if ($options['readOnly']) {
            $editor->setReadOnly(true);
        }

        return $editor;
    }

    public function getIcon()
    {
        return 'icons/phpFile16.png';
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