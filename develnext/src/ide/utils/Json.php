<?php
namespace ide\utils;

use php\format\JsonProcessor;

/**
 * Class Json
 * @package ide\utils
 */
class Json
{
    /**
     * @param $object
     *
     * @return string
     */
    static function encode($object)
    {
        $processor = new JsonProcessor();
        return $processor->format($object);
    }

    /**
     * @param $string
     *
     * @return array
     */
    static function decode($string)
    {
        $processor = new JsonProcessor(JsonProcessor::DESERIALIZE_AS_ARRAYS);
        return $processor->parse($string);
    }
}