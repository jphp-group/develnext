<?php
namespace ide\utils;

use php\format\JsonProcessor;
use php\format\ProcessorException;

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
     * @return mixed
     */
    static function decode($string)
    {
        $processor = new JsonProcessor(JsonProcessor::DESERIALIZE_AS_ARRAYS);
        return $processor->parse($string);
    }

    /**
     * @param $filename
     * @param $data
     */
    static function toFile($filename, $data)
    {
        FileUtils::put($filename, self::encode($data));
    }

    /**
     * @param $filename
     *
     * @return array|null
     */
    static function fromFile($filename)
    {
        try {
            return self::decode(FileUtils::get($filename));
        } catch (ProcessorException $e) {
            return null;
        }
    }
}