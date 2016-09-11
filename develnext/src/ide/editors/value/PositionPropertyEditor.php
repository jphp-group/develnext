<?php
namespace ide\editors\value;

class PositionPropertyEditor extends EnumPropertyEditor
{
    public function __construct()
    {
        parent::__construct([
            'TOP_LEFT' => 'Верх, слева',
            'TOP_CENTER' => 'Верх, по центру',
            'TOP_RIGHT' => 'Верх, справа',

            'CENTER_LEFT'  => 'Центр, слева',
            'CENTER'       => 'Центр',
            'CENTER_RIGHT' => 'Центр, справа',

            'BOTTOM_LEFT' => 'Низ, слева',
            'BOTTOM_CENTER' => 'Низ, по центру',
            'BOTTOM_RIGHT' => 'Низ, справа'
        ]);
    }

    public function getCode()
    {
        return 'position';
    }
}