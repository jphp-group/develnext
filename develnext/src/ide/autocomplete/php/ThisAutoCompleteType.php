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
use ide\editors\ScriptModuleEditor;
use ide\systems\FileSystem;
use php\gui\framework\AbstractForm;
use php\lib\str;

class ThisAutoCompleteType extends AutoCompleteType
{
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
        $class = $region->getLastValue('self');

        $result = [];

        if ($class && $class['constants']) {
            foreach ($class['constants'] as $one) {
                $result[] = new ConstantAutoCompleteItem($one['name'], 'Константа класса ' . $class['name']);
            }
        }

        return $result;
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return PropertyAutoCompleteItem[]
     */
    public function getProperties(AutoComplete $context, AutoCompleteRegion $region)
    {
        $class = $region->getLastValue('self');

        $result = [];

        if ($class && $class['variables']) {
            foreach ($class['variables'] as $one) {
                $result[$one['name']] = new PropertyAutoCompleteItem($one['name'], 'Переменная класса ' . $class['name']);
            }
        }

        $editor = FileSystem::getSelectedEditor();

        if ($editor instanceof FormEditor) {
            $list = $editor->getObjectList();

            foreach ($list as $one) {
                $result[$one->text] = new PropertyAutoCompleteItem($one->text, $one->hint, null, $one->getIcon());
            }

            $moduleEditors = $editor->getModuleEditors();

            foreach ($moduleEditors as $module => $moduleEditor) {
                $list = $moduleEditor->getObjectList();

                foreach ($list as $one) {
                    $result[$one->text] = new PropertyAutoCompleteItem(
                        $one->text,
                        $one->hint . ' (из ' . $moduleEditor->getTitle() . ')',
                        null,
                        $one->getIcon()
                    );
                }
            }

            if ($editor instanceof ScriptModuleEditor) {
                foreach ($editor->getFormEditors() as $formEditor) {
                    $list = $formEditor->getObjectList();

                    foreach ($list as $one) {
                        $result[$one->text] = new PropertyAutoCompleteItem(
                            $one->text,
                            $one->hint . ' (из ' . $formEditor->getTitle() . ')',
                            null,
                            $one->getIcon()
                        );
                    }
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
        $class = $region->getLastValue('self');

        $result = [];

        if ($class && $class['methods']) {
            foreach ($class['methods'] as $one) {
                $result[$one['name']] = new MethodAutoCompleteItem($one['name'], 'Метод класса ' . $class['name'], $one['name'] . '(');
            }

            $reflection = new \ReflectionClass(AbstractForm::class);

            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->isStatic() || $method->isAbstract()) {
                    continue;
                }

                if (str::startsWith($method->getName(), '__')) {
                    continue;
                }

                $result[$method->getName()] = PhpCompleteUtils::methodAutoComplete($method);
            }
        }

        return $result;
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