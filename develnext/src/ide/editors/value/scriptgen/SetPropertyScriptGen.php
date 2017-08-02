<?php
namespace ide\editors\value\scriptgen;

use ide\editors\value\ElementPropertyEditor;
use ide\library\IdeLibraryScriptGeneratorResource;
use php\gui\paint\UXColor;
use php\gui\text\UXFont;
use php\lib\arr;
use php\lib\str;

class SetPropertyScriptGen extends IdeLibraryScriptGeneratorResource
{
    /**
     * @var ElementPropertyEditor
     */
    private $editor;

    /**
     * @var mixed
     */
    private $value;

    /**
     * SetPropertyScriptGen constructor.
     * @param ElementPropertyEditor $editor
     * @param $value
     */
    public function __construct(ElementPropertyEditor $editor, $value)
    {
        parent::__construct(null);
        $this->editor = $editor;
        $this->value = $value;
    }


    public function getName()
    {
        return "Изменить свойство (" . $this->editor->code . ")";
    }

    public function getDescription()
    {
        return "Задать новое значение свойству объекта";
    }

    public function getSourceSyntax()
    {
        return "php";
    }

    public function getSource($param = '')
    {
        $value = $this->value;

        if ($value instanceof UXColor) {
            $value = "'$value->webValue'";
        } else if ($value instanceof UXFont) {
            if ($value->bold && $value->italic) {
                $value = "UXFont::of('$value->family', $value->size, 'BOLD', true)";
            } else if ($value->bold) {
                $value = "UXFont::of('$value->family', $value->size, 'BOLD')";
            } else if ($value->italic) {
                $value = "UXFont::of('$value->family', $value->size, 'THIN', true)";
            } else {
                $value = "UXFont::of('$value->family', $value->size)";
            }
        } else {
            $value = var_export($value, true);
        }

        $code = arr::last(str::split($this->editor->code, '.'));

        if ($param == 'idEmpty') {
            return "
            
// Изменяем значение свойства (#prop.type#) ...
\$this->$code = $value; 
            ";

        } else {
            return <<<"CODE"

// Изменяем значение свойства (#prop.type#) ...
\$this->#object.id#->$code = $value;   
        
CODE;
        }

    }
}