<?php
namespace ide\autocomplete;

abstract class AutoCompleteTypeLoader
{
    abstract public function load($name);
}