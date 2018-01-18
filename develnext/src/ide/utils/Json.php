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
     * @param bool $pretty
     * @return string
     */
    static function encode($object, bool $pretty = false)
    {
        $processor = new JsonProcessor($pretty ? JsonProcessor::SERIALIZE_PRETTY_PRINT : 0);
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
        FileUtils::put($filename, self::encode($data, true));
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