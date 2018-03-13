<?php

/**
 * Error is the base class for all internal PHP errors.
 */
class Error implements Throwable
{
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

    private function __clone()
    {
    }
}