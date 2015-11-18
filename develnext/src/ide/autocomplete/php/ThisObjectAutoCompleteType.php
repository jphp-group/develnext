<?php
namespace ide\autocomplete\php;

use ide\autocomplete\AutoComplete;
use ide\autocomplete\AutoCompleteRegion;
use ide\autocomplete\AutoCompleteType;
use ide\autocomplete\ConstantAutoCompleteItem;
use ide\autocomplete\MethodAutoCompleteItem;
use ide\autocomplete\PropertyAutoCompleteItem;
use ide\autocomplete\StatementAutoCompleteItem;
use ide\autocomplete\VariableAutoCompleteItem;
use ide\editors\FormEditor;
use ide\formats\form\AbstractFormElement;
use ide\scripts\AbstractScriptComponent;
use ide\systems\FileSystem;

/**
 * Class ThisObjectAutoCompleteType
 * @package ide\autocomplete\php
 */
class ThisObjectAutoCompleteType extends AutoCompleteType
{
    protected $id;

    /**
     * @var AbstractFormElement
     */
    protected $element;

    /**
     * ThisObjectAutoCompleteType constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
        $editor = FileSystem::getSelectedEditor();

        if ($editor instanceof FormEditor) {
            foreach ($editor->getObjectList() as $item) {
                if ($item->text == $id) {
                    $this->element = $item->element;
                    break;
                }
            }

            foreach ($editor->getModuleEditors() as $one) {
                foreach ($one->getObjectList() as $item) {
                    if ($item->text == $id) {
                        $this->element = $item->element;
                        break;
                    }
                }
            }
        }
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return StatementAutoCompleteItem[]
     */
    public function getStatements(AutoComplete $context, AutoCompleteRegion $region)
    {
        return [];
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return ConstantAutoCompleteItem[]
     */
    public function getConstants(AutoComplete $context, AutoCompleteRegion $region)
    {
        return [];
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return PropertyAutoCompleteItem[]
     */
    public function getProperties(AutoComplete $context, AutoCompleteRegion $region)
    {
        $result = [];

        if ($this->element) {
            if (!($this->element instanceof AbstractScriptComponent)) {
                $result['x'] = new PropertyAutoCompleteItem('x', 'Позиция X (float)');
                $result['y'] = new PropertyAutoCompleteItem('y', 'Позиция Y (float)');
                $result['width'] = new PropertyAutoCompleteItem('width', 'Ширина (float)');
                $result['height'] = new PropertyAutoCompleteItem('height', 'Высота (float)');
                $result['visible'] = new PropertyAutoCompleteItem('visible', 'Видимость (boolean)');
                $result['enabled'] = new PropertyAutoCompleteItem('enabled', 'Доступность (boolean)');
            }

            foreach ($this->element->getProperties() as $name => $prop) {
                if (!$prop['isCss'] && !$prop['isVirtual']) {
                    $code = $prop['realCode'] ?: $name;

                    $result[$name] = new PropertyAutoCompleteItem($code, $prop['tooltip'] . ' (' . $prop['editor'] . ')');
                }
            }
        }

        return $result;
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return MethodAutoCompleteItem[]
     */
    public function getMethods(AutoComplete $context, AutoCompleteRegion $region)
    {
        return [];
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return VariableAutoCompleteItem[]
     */
    public function getVariables(AutoComplete $context, AutoCompleteRegion $region)
    {
        return [];
    }
}