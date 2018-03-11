<?php
namespace ide\webplatform\editors\value;

use ide\editors\value\ElementPropertyEditor;
use ide\editors\value\EnumPropertyEditor;
use ide\Logger;
use php\gui\UXNode;

class CssClassPropertyEditor extends EnumPropertyEditor
{
    public function __construct(array $variants = [], $editable = false)
    {
        parent::__construct($variants, $editable);

        $this->setSetter(function (ElementPropertyEditor $editor, $value) {
            /** @var UXNode $target */
            $target = $this->designProperties->target;

            foreach ($this->variantKeys as $class) {
                if ($class && $class !== $value) {
                    $target->classes->remove($class);
                }
            }

            if (!$target->classes->has($value)) {
                $target->classes->add($value);
                $this->trigger('change');
            }
        });

        $this->setGetter(function (ElementPropertyEditor $editor) {
            /** @var UXNode $target */
            $target = $this->designProperties->target;

            foreach ($this->variantKeys as $class) {
                if ($class && $target->classes->has($class)) {
                    return $class;
                }
            }

            return '';
        });
    }


    public function getCode()
    {
        return 'cssClass';
    }
}