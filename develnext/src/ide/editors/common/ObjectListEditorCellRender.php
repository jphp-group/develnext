<?php
namespace ide\editors\common;

use ide\Ide;
use php\gui\layout\UXHBox;
use php\gui\paint\UXColor;
use php\gui\UXLabel;
use php\gui\UXListCell;

class ObjectListEditorCellRender
{
    protected $hideHint = false;

    public function hideHint()
    {
        $this->hideHint = true;
    }

    public function __invoke(UXListCell $cell, ObjectListEditorItem $item)
    {
        $cell->graphic = null;
        $cell->text = null;

        $label = new UXLabel($item->text);
        $label->graphic = Ide::get()->getImage($item->graphic, [16, 16]);

        $label->paddingLeft = $item->level * 10;

        if ($this->hideHint) {
            $cell->graphic = $label;
        } else {
            $hintLabel = new UXLabel($item->hint ? ": $item->hint" : "");
            $hintLabel->textColor = UXColor::of('gray');

            $cell->graphic = new UXHBox([$label, $hintLabel]);
        }
    }
}