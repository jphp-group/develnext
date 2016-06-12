<?php
namespace ide\autocomplete;


class StatementAutoCompleteItem extends AutoCompleteItem
{
    public function __construct($name, $description = '', $insert = null, $icon = null, $style = null)
    {
        $style = $style ?: '-fx-text-fill: #1274c0';

        parent::__construct($name, $description, $insert, $icon, $style);
    }

    public function getDefaultIcon()
    {
        return 'icons/tag16.png';
    }
}