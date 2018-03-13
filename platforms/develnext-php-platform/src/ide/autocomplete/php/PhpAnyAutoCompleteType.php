<?php
namespace ide\autocomplete\php;

use develnext\lexer\inspector\AbstractInspector;
use framework\localization\TranslationText;
use ide\autocomplete\AutoComplete;
use ide\autocomplete\AutoCompleteInsert;
use ide\autocomplete\AutoCompleteRegion;
use ide\autocomplete\AutoCompleteType;
use ide\autocomplete\ConstantAutoCompleteItem;
use ide\autocomplete\MethodAutoCompleteItem;
use ide\autocomplete\PropertyAutoCompleteItem;
use ide\autocomplete\StatementAutoCompleteItem;
use ide\autocomplete\VariableAutoCompleteItem;
use ide\bundle\AbstractJarBundle;
use ide\editors\CodeEditor;
use ide\editors\common\CodeTextArea;
use ide\Ide;
use ide\project\behaviours\BundleProjectBehaviour;
use ide\project\behaviours\PhpProjectBehaviour;
use ide\project\Project;
use php\gui\designer\UXAbstractCodeArea;
use php\lib\arr;
use php\lib\fs;
use php\lib\str;
use php\util\Regex;

/**
 * Class PhpAnyAutoCompleteType
 * @package ide\autocomplete\php
 */
class PhpAnyAutoCompleteType extends AutoCompleteType
{
    /**
     * @var AbstractInspector
     */
    protected $inspector;

    protected $kind = '~any';

    /**
     * PhpAnyAutoCompleteType constructor.
     * @param string $kind
     * @param AbstractInspector $inspector
     */
    public function __construct($kind = '~any', AbstractInspector $inspector = null)
    {
        $this->inspector = $inspector;
        $this->kind = $kind;
    }

    public static function appendUseClass(UXAbstractCodeArea $area, $use)
    {
        $useString = "use " . $use . ";";

        $text = $area->text;

        $pos = -1;
        $caret = $area->caretPosition;

        $regex = Regex::of('use[ ]+([0-9\\_a-z\\\\]+)', Regex::DOTALL | Regex::CASE_INSENSITIVE)->with($text);
        if ($regex->find()) {
            $pos = $regex->start(0);
        } else {
            $useString = "\n$useString";

            $regex = Regex::of('namespace[ ]+([0-9\\_a-z\\\\]+\\;)', Regex::DOTALL | Regex::CASE_INSENSITIVE)->with($text);

            if ($regex->find()) {
                $pos = $regex->end(0) + 1;
            } else {
                $regex = Regex::of('namespace[ ]+([0-9\\_a-z\\\\]+[ \\r\\t\\n]+\\{)', Regex::DOTALL | Regex::CASE_INSENSITIVE)->with($text);

                if ($regex->find()) {
                    $pos = $regex->end(0) + 1;
                } else {
                    $regex = Regex::of('(\\<\\?(php)?)', Regex::DOTALL | Regex::CASE_INSENSITIVE)->with($text);

                    if ($regex->find()) {
                        $pos = $regex->end(0) + 1;
                    } else {
                        return false;
                    }
                }
            }
        }

        $useString .= "\n";
        $area->insertText($pos, $useString);

        if ($pos < $caret) {
            $area->caretPosition = $caret + str::length($useString);
        }

        return true;
    }

    protected function insertClassName(AutoCompleteInsert $insert, $package = null)
    {
        $regex = Regex::of('use[ ]+([0-9\\,\\ \\_a-z\\\\]+)', Regex::DOTALL | Regex::CASE_INSENSITIVE);
        $regex = $regex->with($text = $insert->getArea()->text);

        $use = $package ?: $insert->getValue();

        while ($regex->find()) {
            $names = $regex->group(1);

            foreach (str::split($names, ',') as $name) {
                $name = str::trim($name);

                if (str::equalsIgnoreCase($name, $use)) {
                    $insert->setValue(fs::name($insert->getValue()));
                    return;
                }
            }
        }

        if (self::appendUseClass($insert->getArea(), $use)) {
            $insert->setValue(fs::name($insert->getValue()));
        } else {
            $insert->setValue("\\" . "{$insert->getValue()}");
        }
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return \ide\autocomplete\ConstantAutoCompleteItem[]
     */
    public function getConstants(AutoComplete $context, AutoCompleteRegion $region)
    {
        $result = [];

        if (in_array($this->kind, ['~any'])) {
            $result['__CLASS__'] = new ConstantAutoCompleteItem('__CLASS__');
            $result['__METHOD__'] = new ConstantAutoCompleteItem('__METHOD__');
            $result['__FUNCTION__'] = new ConstantAutoCompleteItem('__FUNCTION__');
            $result['__DIR__'] = new ConstantAutoCompleteItem('__DIR__');
            $result['__FILE__'] = new ConstantAutoCompleteItem('__FILE__');
            $result['__NAMESPACE__'] = new ConstantAutoCompleteItem('__NAMESPACE__');
            $result['__LINE__'] = new ConstantAutoCompleteItem('__LINE__');

            foreach ($this->inspector->getConstants() as $constant) {
                $result[$constant->name] = new ConstantAutoCompleteItem($constant->name, "$constant->value");
            }

            foreach ($this->inspector->getTypes() as $type) {
                if ($type->data['deprecated']) {
                    continue;
                }

                $import = $type->fulledName;
                $name = fs::name($import);

                $description = $import;

                $style = '';

                if ($type->kind == 'INTERFACE') {
                    $description = "$description (interface)";
                } elseif ($type->kind == 'TRAIT') {
                    $description = "$description (trait)";
                } elseif ($type->abstract) {
                    $description = "$description (abstract)";
                }

                if ($type->packages) {
                    $description = "$description, packages: " . str::join($type->packages, ', ');
                }

                $result[$name] = $c = new ConstantAutoCompleteItem(
                    $name,
                    $description,
                    function (AutoCompleteInsert $insert) use ($import, $type) {
                        $insert->setValue($import);

                        $package = null;

                        if ($php = PhpProjectBehaviour::get()) {
                            if ($php->getImportType() == 'package') {
                                if ($type->packages) {
                                    $package = arr::first($type->packages);
                                }
                            }
                        }

                        $this->insertClassName($insert, $package);
                    },
                    'icons/class16.png',
                    $style
                );

                $c->setContent($type->data['content']);

                $constructor = TypeAccessAutoCompleteType::findTypeMethod(
                    $this->inspector, 'dynamic', $type, '__construct'
                );

                if ($constructor) {
                    $item = PhpCompleteUtils::methodAutoComplete2($constructor, false);
                    $item->setName('Конструктор __construct');
                    $c->addSubItem($item);
                }
            }

            foreach ($context->getGlobalRegion()->getValues('use') as $one) {
                $name = fs::name($one['name']);

                $result[$name] = new ConstantAutoCompleteItem($name, "use {$one['name']}");
            }

            /*foreach ($context->getGlobalRegion()->getValues('class') as $one) {
                $result[$one->name] = new ConstantAutoCompleteItem($one->name, 'Класс ' . $one['namespace'] . "\\" . $one['name']);
            }*/

            foreach ($context->getGlobalRegion()->getValues('constant') as $one) {
                $result[$one['name']] = new ConstantAutoCompleteItem($one['name'], 'Константа');
            }

            foreach ($region->getValues('constant') as $one) {
                $result[$one['name']] = new ConstantAutoCompleteItem($one['name'], 'Константа');
            }
        }

        return $result;
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return \ide\autocomplete\PropertyAutoCompleteItem[]
     */
    public function getProperties(AutoComplete $context, AutoCompleteRegion $region)
    {
        return [];
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return \ide\autocomplete\MethodAutoCompleteItem[]
     */
    public function getMethods(AutoComplete $context, AutoCompleteRegion $region)
    {
        if (in_array($this->kind, ['~any'])) {
            if ($this->inspector) {
                $result = [];

                foreach ($this->inspector->getFunctions() as $func) {
                    $result[$func->fulledName] = PhpCompleteUtils::methodAutoComplete2($func);
                }

                return $result;
            }
        }

        return [];
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return \ide\autocomplete\VariableAutoCompleteItem[]
     */
    public function getVariables(AutoComplete $context, AutoCompleteRegion $region)
    {
        $result = [];

        $result['GLOBALS'] = new VariableAutoCompleteItem('GLOBALS', 'array', $this->kind == '~any' ? '$GLOBALS' : null);
        $result['_ENV'] = new VariableAutoCompleteItem('_ENV', 'array', $this->kind == '~any' ? '$_ENV' : null);

        foreach ($context->getGlobalRegion()->getValues('variable') as $one) {
            $result[$one['name']] = new VariableAutoCompleteItem($one['name'], $one['type'], ($this->kind == '~any') ? "$" . $one['name'] : null);
        }

        foreach ($region->getValues('variable') as $one) {
            $result[$one['name']] = new VariableAutoCompleteItem($one['name'], $one['type'], ($this->kind == '~any') ? "$" . $one['name'] : null);
        }

        return $result;
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return StatementAutoCompleteItem[]
     */
    public function getStatements(AutoComplete $context, AutoCompleteRegion $region)
    {
        if (in_array($this->kind, ['~any'])) {
            $result = [
                new StatementAutoCompleteItem('if', 'Условие (если)', 'if (#)'),
                new StatementAutoCompleteItem('else', 'Иначе ...', 'else '),
                new StatementAutoCompleteItem('elseif', 'Иначе если ...', 'elseif (#)'),
                new StatementAutoCompleteItem('for', 'Цикл', 'for ('),
                new StatementAutoCompleteItem('foreach', 'Цикл по массиву', 'foreach (#)'),
                new StatementAutoCompleteItem('while', 'Цикл', 'while (#)'),
                new StatementAutoCompleteItem('do', 'Цикл', 'do { '),
                new StatementAutoCompleteItem('function', 'Объявление функции', 'function '),
                new StatementAutoCompleteItem('fn', 'Анонимная функция (короткий синтаксис)', 'fn'),
                new StatementAutoCompleteItem('class', 'Объявление класса', 'class '),
                new StatementAutoCompleteItem('namespace', 'Объявление пространства имен', 'namespace '),
                new StatementAutoCompleteItem('use', 'Подключение класса', 'use '),
                new StatementAutoCompleteItem('switch', 'Множественное условие', 'switch (#)'),
                new StatementAutoCompleteItem('case', 'Если от множественного условия', 'case (#)'),
                new StatementAutoCompleteItem('default', 'По умолчанию в множественном условии switch', 'default: '),
                new StatementAutoCompleteItem('global', 'Глобальная переменная', 'global $'),
                new StatementAutoCompleteItem('const', 'Объявление константы', 'const '),
                new StatementAutoCompleteItem('var', 'Объявление переменной класса', 'var '),
                new StatementAutoCompleteItem('public', 'Публичное', 'public '),
                new StatementAutoCompleteItem('protected', 'Защищенное', 'protected '),
                new StatementAutoCompleteItem('private', 'Приватное', 'private '),
                new StatementAutoCompleteItem('parent::', 'Обращение к родительскому классу', 'parent::'),
                new StatementAutoCompleteItem('self', 'Статичное обращение к классу', 'self'),
                new StatementAutoCompleteItem('static', 'Статичное обращение к классу', 'static'),
                new StatementAutoCompleteItem('array', 'Тип массив', 'array '),
                new StatementAutoCompleteItem('new', '', 'new '),
                new StatementAutoCompleteItem('instanceof', '', 'instanceof '),
                new StatementAutoCompleteItem('insteadof', '', 'insteadof '),
                new StatementAutoCompleteItem('true', '', 'true'),
                new StatementAutoCompleteItem('false', '', 'false'),
                new StatementAutoCompleteItem('null', '', 'null'),
                new StatementAutoCompleteItem('as', '', 'as '),
                new StatementAutoCompleteItem('return', '', 'return '),
                new StatementAutoCompleteItem('goto', '', 'goto '),
                new StatementAutoCompleteItem('unset', '', 'unset(#)'),
                new StatementAutoCompleteItem('isset', '', 'isset(#)'),
                new StatementAutoCompleteItem('empty', '', 'empty(#)'),
                new StatementAutoCompleteItem('die', '', 'die()'),
                new StatementAutoCompleteItem('exit', '', 'exit()'),
                new StatementAutoCompleteItem('interface', '', 'interface '),
                new StatementAutoCompleteItem('trait', '', 'trait '),
                new StatementAutoCompleteItem('try', '', 'try {'),
                new StatementAutoCompleteItem('catch', '', 'catch (#)'),
                new StatementAutoCompleteItem('finally', '', 'finally {'),
                new StatementAutoCompleteItem('throw new', '', 'throw new '),
                new StatementAutoCompleteItem('extends', '', 'extends '),
                new StatementAutoCompleteItem('implements', '', 'implements '),
                new StatementAutoCompleteItem('list', '', 'list(#)'),
                new StatementAutoCompleteItem('callable', 'Тип функция/метод', 'callable '),
                new StatementAutoCompleteItem('iterable', 'Тип, перечисляемый', 'iterable '),
                new StatementAutoCompleteItem('bool', 'Тип, булево', 'bool '),
                new StatementAutoCompleteItem('int', 'Тип, целое число', 'int '),
                new StatementAutoCompleteItem('float', 'Тип, вещественное число', 'float '),
                new StatementAutoCompleteItem('string', 'Тип, строка', 'string '),
                new StatementAutoCompleteItem('void', 'Отсутствующий тип', 'void '),
                new StatementAutoCompleteItem('break', 'Выйти из цикла или switch', 'break;'),
                new StatementAutoCompleteItem('continue', 'К следующей итерации цикла', 'continue;'),
                new StatementAutoCompleteItem('echo', 'Распечатать в вывод консоли', 'echo '),
                new StatementAutoCompleteItem('and', 'Логическое И', 'and'),
                new StatementAutoCompleteItem('or', 'Логическое ИЛИ', 'or'),
                new StatementAutoCompleteItem('xor', '', 'xor'),
            ];

            foreach ($this->inspector->getSnippets() as $snippet) {
                $desc = new TranslationText($snippet->description);

                $result[] = new StatementAutoCompleteItem($snippet->name, $desc->get(Ide::get()->getLanguage()->getCode()), $snippet->code, 'icons/script16.png');
            }

            return $result;
        }

        return [];
    }
}
