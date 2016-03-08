<?php
namespace ide\autocomplete\php;

use ide\autocomplete\MethodAutoCompleteItem;
use php\lib\str;

abstract class PhpCompleteUtils
{
    /**
     * @param \ReflectionMethod $method
     * @return string
     */
    static function methodParamsString(\ReflectionMethod $method)
    {
        $_params = [];

        foreach ($method->getParameters() as $one) {
            /** @var \ReflectionParameter $one */
            $item= "\${$one->getName()}";

            if ($one->isPassedByReference()) {
                $item = "&$item";
            }

            if ($one->isArray()) {
                $item = "array $item";
            } elseif ($one->isCallable()) {
                $item = "callable $item";
            } elseif ($one->getClass()) {
                $item = "{$one->getClass()->getName()} $item";
            }

            if ($one->isOptional()) {
                if ($one->getDefaultValueConstantName()) {
                    $item .= " = {$one->getDefaultValueConstantName()}";
                } else {
                    $value = var_export($one->getDefaultValue());

                    if ($one->allowsNull()) {
                        $value = 'null';
                    }

                    $item .= " = {$value}";
                }
            }

            $_params[] = $item;
        }

        if ($_params) {
            $description = '(' . str::join($_params, ', ') . ')';
        } else {
            $description = '';
        }

        return $description;
    }

    static function methodAutoComplete(\ReflectionMethod $method)
    {
        $insert = $method->getName() . '(';

        $description = self::methodParamsString($method);

        if ($method->isStatic()) {
            return MethodAutoCompleteItem::func(
                $method->getName(), $description, $insert
            );
        } else {
            return new MethodAutoCompleteItem($method->getName(), $description, $insert);
        }
    }
}