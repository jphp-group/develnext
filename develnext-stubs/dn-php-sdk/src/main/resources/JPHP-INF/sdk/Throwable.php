<?php

interface Throwable
{
    /**
     * @return string
     */
    function getMessage();

    /**
     * @return int
     */
    function getCode();

    /**
     * @return string
     */
    function getFile();

    /**
     * @return int
     */
    function getLine();

    /**
     * @return array
     */
    function getTrace();

    /**
     * @return string
     */
    function getTraceAsString();

    /**
     * @return Throwable|null
     */
    function getPrevious();

    /**
     * @return string
     */
    function __toString();
}