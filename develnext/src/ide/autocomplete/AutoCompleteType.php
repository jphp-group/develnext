<?php
namespace ide\autocomplete;

/**
 * Тип (сущность)
 *
 * Class AutoCompleteType
 * @package ide\autocomplete
 */
abstract class AutoCompleteType
{
    abstract public function getConstants();
    abstract public function getProperties();
    abstract public function getMethods();
}