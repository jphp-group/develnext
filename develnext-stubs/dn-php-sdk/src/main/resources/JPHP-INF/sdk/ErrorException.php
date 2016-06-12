<?php

/**
 * Class ErrorException
 */
class ErrorException extends Exception
{
    /**
     * @var int
     */
    protected $severity;

    /**
     * @param string $message
     * @param int $code
     * @param int $severity
     * @param string $filename
     * @param int $lineno
     * @param Exception|null $previous
     */
    function __construct($message = "", $code = 0, $severity = E_ERROR, $filename = __FILE__, $lineno = __LINE__, Exception $previous = null)
    {
    }

    /**
     * @return int
     */
    public function getSeverity()
    {
    }
}