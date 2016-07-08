<?php
namespace ide\autocomplete\php;

use develnext\lexer\inspector\entry\FunctionEntry;
use develnext\lexer\inspector\entry\MethodEntry;
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

    static function methodParamsString2(FunctionEntry $method)
    {
        $_params = [];
        $optional = false;

        foreach ($method->arguments as $arg) {
            $item = "\${$arg->name}";

            if ($arg->reference) {
                $item = "&$item";
            }

            if ($arg->type) {
                $item = "$arg->type $item";
            }

            if ($arg->value) {
                $item = "$item = $arg->value";
            }

            if ($arg->optional && !$optional) {
                $item = "[$item";
                $optional = true;
            }

            $_params[] = $item;
        }

        if ($_params) {
            $description = '(' . str::join($_params, ', ') . ($optional ? ']' : '') . ')';
        } else {
            $description = "";
        }

        if ($method->data['returnType']) {
            if ($description) {
                $description = "$description: {$method->data['returnType']}";
            } else {
                $description = $method->data['returnType'];
            }
        }

        return $description;
    }

    static function methodAutoComplete2(FunctionEntry $method, $bold = true)
    {
        $insert = $method->name . '(';

        if ($method->arguments) {
            $insert .= '#';
        }

        $insert .= ')';

        $description = self::methodParamsString2($method);

        if ($method->static) {
            $item = MethodAutoCompleteItem::func(
                $method->name, $description, $insert, null, $bold ? ';' : '-fx-text-fill: #4c4c4c;'
            );
        } else {
            $item = new MethodAutoCompleteItem($method->name, $description, $insert, null, $bold ? ';' : '-fx-text-fill: #4c4c4c;');
        }

        $item->setContent($method->data['content']);
        return $item;
    }
}