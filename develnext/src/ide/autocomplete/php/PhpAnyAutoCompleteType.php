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
use ide\bundle\AbstractJarBundle;
use ide\Ide;
use ide\project\behaviours\BundleProjectBehaviour;
use ide\project\Project;
use php\lib\fs;

/**
 * Class PhpAnyAutoCompleteType
 * @package ide\autocomplete\php
 */
class PhpAnyAutoCompleteType extends AutoCompleteType
{
    protected $kind = '~any';

    /**
     * PhpAnyAutoCompleteType constructor.
     * @param string $kind
     */
    public function __construct($kind = '~any')
    {
        $this->kind = $kind;
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
            $bundle = BundleProjectBehaviour::get();

            if ($bundle) {
                foreach ($bundle->fetchAllBundles(Project::ENV_ALL) as $one) {
                    if ($one instanceof AbstractJarBundle) {
                        foreach ($one->getUseImports() as $import) {
                            $name = fs::name($import);

                            $result[$name] = new ConstantAutoCompleteItem($name, $import);
                        }
                    }
                }
            }

            foreach ($context->getGlobalRegion()->getValues('use') as $one) {
                $name = fs::name($one['name']);

                $result[$name] = new ConstantAutoCompleteItem($name, "use {$one['name']}");
            }

            foreach ($context->getGlobalRegion()->getValues('class') as $one) {
                $result[$one['name']] = new ConstantAutoCompleteItem($one['name'], 'Класс ' . $one['namespace'] . "\\" . $one['name']);
            }

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
            return [
                MethodAutoCompleteItem::func('pre', 'Вывести в сообщение отладочную информацию значения', 'pre('),
                MethodAutoCompleteItem::func('dump', 'Вывести в сообщение подробную отладочную информацию значения', 'dump('),
                MethodAutoCompleteItem::func('alert', 'Всплывающее сообщение', 'alert('),
                MethodAutoCompleteItem::func('execute', 'Выполнить системную команду', 'execute('),
                MethodAutoCompleteItem::func('browse', 'Открыть url страницу', 'browse('),
                MethodAutoCompleteItem::func('open', 'Открыть файл', 'open('),
                MethodAutoCompleteItem::func('wait', 'Пауза в млсек', 'wait('),
                MethodAutoCompleteItem::func('waitAsync', 'Ассинхронная пауза в млсек', 'waitAsync('),
            ];
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

        foreach ($context->getGlobalRegion()->getValues('variable') as $one) {
            $result[$one['name']] = new VariableAutoCompleteItem($one['name'], $one['type']);
        }

        foreach ($region->getValues('variable') as $one) {
            $result[$one['name']] = new VariableAutoCompleteItem($one['name'], $one['type']);
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
            return [
                new StatementAutoCompleteItem('if', 'Условаие (если)', 'if ('),
                new StatementAutoCompleteItem('else', 'Иначе ...', 'else '),
                new StatementAutoCompleteItem('elseif', 'Иначе если ...', 'elseif ('),
                new StatementAutoCompleteItem('for', 'Цикл', 'for ('),
                new StatementAutoCompleteItem('while', 'Цикл', 'while ('),
                new StatementAutoCompleteItem('do', 'Цикл', 'do { '),
                new StatementAutoCompleteItem('function', 'Объявление функции', 'function '),
                new StatementAutoCompleteItem('class', 'Объявление класса', 'class '),
                new StatementAutoCompleteItem('namespace', 'Объявление пространства имен', 'namespace '),
                new StatementAutoCompleteItem('use', 'Подключение класса', 'use '),
                new StatementAutoCompleteItem('switch', 'Множественное условие', 'switch ('),
                new StatementAutoCompleteItem('case', 'Если от множественного условия', 'case '),
                new StatementAutoCompleteItem('default', 'По-умолчанию в множественном условии switch', 'default: '),
                new StatementAutoCompleteItem('global', 'Глобальная переменная', 'global $'),
                new StatementAutoCompleteItem('const', 'Объявление константы', 'const '),
                new StatementAutoCompleteItem('var', 'Объявление переменной класса', 'var '),
                new StatementAutoCompleteItem('public', 'Публичное', 'public '),
                new StatementAutoCompleteItem('protected', 'Защищенное', 'protected '),
                new StatementAutoCompleteItem('private', 'Приватное', 'private '),
                new StatementAutoCompleteItem('parent::', 'Обращение к родительскому классу', 'parent::'),
                new StatementAutoCompleteItem('self::', 'Статичное обращение к классу', 'self::'),
                new StatementAutoCompleteItem('static::', 'Статичное обращение к классу', 'static::'),
                new StatementAutoCompleteItem('array', 'Тип массив', 'array '),
                new StatementAutoCompleteItem('callable', 'Тип функция/метод', 'callable '),
                new StatementAutoCompleteItem('break', 'Выйти из цикла или switch', 'break;'),
                new StatementAutoCompleteItem('continue', 'К следующей итерации цикла', 'continue;'),
                new StatementAutoCompleteItem('echo', 'Распечатать в вывод консоли', 'echo '),
            ];
        }

        return [];
    }
}