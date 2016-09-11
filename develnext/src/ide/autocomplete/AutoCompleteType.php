<?php
namespace ide\autocomplete;

/**
 * Тип (сущность)
 *
 * Class AutoCompleteType
 * @package ide\autocomplete
 */
abstract class AutoCompleteType
{
    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return StatementAutoCompleteItem[]
     */
    abstract public function getStatements(AutoComplete $context, AutoCompleteRegion $region);

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return ConstantAutoCompleteItem[]
     */
    abstract public function getConstants(AutoComplete $context, AutoCompleteRegion $region);

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return PropertyAutoCompleteItem[]
     */
    abstract public function getProperties(AutoComplete $context, AutoCompleteRegion $region);

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return MethodAutoCompleteItem[]
     */
    abstract public function getMethods(AutoComplete $context, AutoCompleteRegion $region);

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return VariableAutoCompleteItem[]
     */
    abstract public function getVariables(AutoComplete $context, AutoCompleteRegion $region);
}