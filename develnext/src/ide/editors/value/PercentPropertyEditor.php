<?php
namespace ide\editors\value;

use php\lib\Str;
use php\lib\String;

/**
 * Class IntegerPropertyEditor
 * @package ide\editors\value
 */
class PercentPropertyEditor extends SimpleTextPropertyEditor
{
    public function __construct(callable $getter = null, callable $setter = null)
    {
        parent::__construct($getter, $setter);

        $this->setter = function (ElementPropertyEditor $editor, $value) {
            $target = $this->designProperties->target;

            if ($value > 100) {
                $value = 100;
            }

            $target->{$editor->code} = $value / 100;
        };

        $this->getter = function (ElementPropertyEditor $editor) {
            $target = $this->designProperties->target;

            return round($target->{$editor->code} * 100);
        };
    }

    public function getNormalizedValue($value)
    {
        return (int) $value;
    }

    public function getCode()
    {
        return 'percent';
    }
}