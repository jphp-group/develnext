<?php
namespace ide\autocomplete;

abstract class AutoCompleteTypeRule
{
    /**
     * @var AutoComplete
     */
    protected $complete;

    /**
     * AutoCompleteTypeRule constructor.
     * @param AutoComplete $complete
     */
    public function __construct(AutoComplete $complete)
    {
        $this->complete = $complete;
    }

    abstract public function identifyType($string);
}