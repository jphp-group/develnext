<?php
namespace ide\editors\value;

use ide\forms\FontPropertyEditorForm;
use php\gui\event\UXMouseEvent;
use php\gui\text\UXFont;
use php\gui\UXList;
use php\lib\Str;
use php\lib\String;
use php\util\Flow;

class StringListPropertyEditor extends TextPropertyEditor
{
    public function __construct(callable $getter = null, callable $setter = null)
    {
        parent::__construct(
            function (ElementPropertyEditor $editor) {
                $target = $this->designProperties->target;

                $value = $target->{$editor->code};

                if ($value instanceof UXList) {
                    return Flow::of($value)->toString("\n");
                } else {
                    throw new \Exception("Unable using StringList property editor for non UXList properties");
                }
            },
            function (ElementPropertyEditor $editor, $value) {
                $target = $this->designProperties->target;

                $list = $target->{$editor->code};

                if ($list instanceof UXList) {
                    $list->clear();

                    $lines = Str::split($value, "\n");
                    $list->addAll($lines);
                }
            }
        );
    }

    public function makeUi()
    {
        $result = parent::makeUi();
        return $result;
    }

    public function getCode()
    {
        return 'stringList';
    }
}