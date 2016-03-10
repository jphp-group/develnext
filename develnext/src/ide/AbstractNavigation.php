<?php
namespace ide;

/**
 * Class AbstractNavigation
 * @package ide
 */
abstract class AbstractNavigation
{
    /**
     * @param $query
     * @return bool
     */
    abstract public function accept($query);

    /**
     * @param $query
     * @return bool
     */
    abstract public function navigate($query);
}