<?php

/**
 * Exception is the base class for all Exceptions in PHP 5, and the base class for all user exceptions in PHP 7.
 */
class Exception implements Throwable
{
    /**
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    function __construct($message = "", $code = 0, Exception $previous = null)
    {
    }

    /**
     * @return string
     */
    function getMessage()
    {
        // TODO: Implement getMessage() method.
    }

    /**
     * @return int
     */
    function getCode()
    {
        // TODO: Implement getCode() method.
    }

    /**
     * @return string
     */
    function getFile()
    {
        // TODO: Implement getFile() method.
    }

    /**
     * @return int
     */
    function getLine()
    {
        // TODO: Implement getLine() method.
    }

    /**
     * @return array
     */
    function getTrace()
    {
        // TODO: Implement getTrace() method.
    }

    /**
     * @return string
     */
    function getTraceAsString()
    {
        // TODO: Implement getTraceAsString() method.
    }

    /**
     * @return Throwable|null
     */
    function getPrevious()
    {
        // TODO: Implement getPrevious() method.
    }

    /**
     * @return string
     */
    function __toString()
    {
        // TODO: Implement __toString() method.
    }
}