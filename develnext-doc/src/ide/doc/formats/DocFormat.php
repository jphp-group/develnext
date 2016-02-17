<?php
namespace ide\doc\formats;

use ide\doc\editors\DocEditor;
use ide\doc\editors\DocEntryEditor;
use ide\editors\AbstractEditor;
use ide\formats\AbstractFormat;
use php\lib\str;

/**
 * Class DocFormat
 * @package ide\doc\formats
 */
class DocFormat extends AbstractFormat
{
    /**
     * @param $file
     * @return AbstractEditor
     */
    public function createEditor($file)
    {
        if (str::startsWith($file, '~doc/edit/')) {
            return new DocEntryEditor($file);
        }

        return new DocEditor($file);
    }

    public function getTitle($path)
    {
        return "Документация";
    }

    public function getIcon()
    {
        return "icons/help16.png";
    }

    /**
     * @param $file
     * @return bool
     */
    public function isValid($file)
    {
        return str::startsWith($file, "~doc");
    }

    /**
     * @param $any
     * @return mixed
     */
    public function register($any)
    {

    }
}