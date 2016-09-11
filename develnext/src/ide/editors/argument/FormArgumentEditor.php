<?php
namespace ide\editors\argument;

class FormArgumentEditor extends MixedArgumentEditor
{
    public function getCode()
    {
        return "form";
    }

    protected function valueTypes()
    {
        return ['form', 'variable', 'expr'];
    }
}