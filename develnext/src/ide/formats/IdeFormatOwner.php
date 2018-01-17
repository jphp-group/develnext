<?php
namespace ide\formats;

use ide\Ide;
use php\lib\reflect;

/**
 * Class IdeFormatOwner
 * @package ide\formats
 */
trait IdeFormatOwner
{
    /**
     * @var AbstractFormat[]
     */
    protected $formats = [];

    /**
     * @param AbstractFormat $format
     */
    public function registerFormat(AbstractFormat $format)
    {
        $class = reflect::typeOf($format);

        if (isset($this->formats[$class])) {
            return;
        }

        foreach ($format->getRequireFormats() as $el) {
            $this->registerFormat($el);
        }

        $this->formats[$class] = $format;
    }

    /**
     * @param $class
     *
     * @return AbstractFormat
     */
    public function getRegisteredFormat($class)
    {
        return $this->formats[$class];
    }

    /**
     * @return AbstractFormat[]
     */
    public function getRegisteredFormats()
    {
        return $this->formats;
    }
}