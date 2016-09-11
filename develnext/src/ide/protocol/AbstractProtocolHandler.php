<?php
namespace ide\protocol;

abstract class AbstractProtocolHandler
{
    /**
     * @param string $query
     * @return bool
     */
    abstract public function isValid($query);

    /**
     * @param $query
     * @return bool
     */
    abstract public function handle($query);
}