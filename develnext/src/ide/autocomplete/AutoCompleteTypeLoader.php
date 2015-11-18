<?php
namespace ide\autocomplete;

abstract class AutoCompleteTypeLoader
{
    /**
     * @param $name
     * @return AutoCompleteType
     */
    abstract public function load($name);
}