<?php
namespace ide\formats;

use ide\editors\AbstractEditor;
use php\io\File;

/**
 * Class AbstractFormat
 * @package ide\formats
 */
abstract class AbstractFormat
{
    /**
     * @var AbstractFormat[]
     */
    protected $requireFormats = [];

    public function getIcon()
    {
        return null;
    }

    public function getTitle($path)
    {
        return File::of($path)->getName();
    }

    /**
     * @param AbstractFormat $format
     */
    protected function requireFormat(AbstractFormat $format)
    {
        $this->requireFormats[get_class($format)] = $format;
    }

    /**
     * @return AbstractFormat[]
     */
    public function getRequireFormats()
    {
        return $this->requireFormats;
    }

    /**
     * @param $file
     * @return AbstractEditor
     */
    abstract public function createEditor($file);

    /**
     * @param $file
     * @return bool
     */
    abstract public function isValid($file);

    /**
     * @param $any
     * @return mixed
     */
    abstract public function register($any);
}