<?php
namespace ide\action;

use php\jsoup\Document;
use php\lib\Str;
use php\xml\DomElement;

abstract class AbstractSimpleActionType extends AbstractActionType
{
    /**
     * @return array
     */
    function attributes()
    {
        return [];
    }

    function fetchFieldValue(Action $action, $field, $value)
    {
        $type = $action->{"$field-type"};

        switch ($type) {
            case 'variable':
                if (!$value) {
                    $value = 'any';
                }

                if ($value[0] != '$') {
                    $value = '$' . $value;
                }

                return $value;

            case 'property':
                return "\$this->$value";

            case 'string':
                return "'$value'";

            case 'integer':
                return ((int) $value) . '';

            case 'float':
                return ((double) $value) . '';

            case 'expr':
                return $value;

            default:
                $type = $this->attributes()[$field];

                switch ($type) {
                    case 'string':
                        return "'$value'";
                    case 'integer':
                        return ((int) $value) . '';
                    case 'float':
                        return ((float) $value) . '';
                    case 'boolean':
                        return $value ? 'true' : 'false';
                    case 'expr':
                        return $value;
                }
        }

        return parent::fetchFieldValue($action, $field, $value);
    }

    /**
     * @param Action $action
     * @param DomElement $element
     * @param Document $document
     */
    function serialize(Action $action, DomElement $element, Document $document)
    {
        foreach ($this->attributes() as $name => $info) {
            $element->setAttribute("{$name}-type", $action->{"$name-type"});

            $element->setAttribute($name, $action->{$name});
        }
    }

    /**
     * @param Action $action
     * @param DomElement $element
     */
    function unserialize(Action $action, DomElement $element)
    {
        foreach ($this->attributes() as $name => $info) {
            $action->{$name} = $element->getAttribute($name);
            $action->{"$name-type"} = $element->getAttribute("$name-type");
        }
    }
}