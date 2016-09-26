<?php
namespace facade;

use php\format\JsonProcessor;
use php\io\IOException;
use php\io\Stream;

/**
 * Class Json
 */
abstract class Json
{
    /**
     * @param $data
     *
     * @param bool $prettyPrint
     *
     * @return string
     */
    static function encode($data, $prettyPrint = true)
    {
        $json = new JsonProcessor($prettyPrint ? JsonProcessor::SERIALIZE_PRETTY_PRINT : 0);
        return $json->format($data);
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
        Stream::putContents($filename, self::encode($data));
    }

    /**
     * @param $filename
     *
     * @return array|null
     */
    static function fromFile($filename)
    {
        try {
            return self::decode(Stream::getContents($filename));
        } catch (IOException $e) {
            return null;
        } catch (ProcessorException $e) {
            return null;
        }
    }
}