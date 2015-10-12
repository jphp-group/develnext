<?php
namespace ide\autocomplete;
use php\lib\Items;
use php\lib\Str;
use phpx\parser\SourceTokenizer;

/**
 * Class AutoComplete
 * @package ide\autocomplete
 */
class AutoComplete
{
    /**
     * @var AutoComplete
     */
    public $context = null;

    /**
     * @var AutoCompleteTypeRule[]
     */
    public $rules = [];

    /**
     * @var AutoCompleteTypeLoader[]
     */
    public $loader = [];

    /**
     * @param string $prefix
     * @return null|string
     */
    public function identifyType($prefix)
    {
        if ($this->context) {
            $result = $this->context->identifyType($prefix);

            if ($result) {
                return $result;
            }
        }

        foreach ($this->rules as $rule) {
            if ($result = $rule->identifyType($prefix)) {
                return $result;
            }
        }

        return null;
    }

    /**
     * @param string $name
     * @return null|AutoCompleteType
     */
    public function fetchType($name)
    {
        if ($this->context) {
            $result = $this->context->fetchType($name);

            if ($result) {
                return $result;
            }
        }

        foreach ($this->loader as $loader) {
            if ($result = $loader->load($name)) {
                return $result;
            }
        }

        return null;
    }

    /**
     * @param AutoCompleteTypeRule $rule
     */
    public function registerTypeRule(AutoCompleteTypeRule $rule)
    {
        $this->rules[get_class($rule)] = $rule;
    }

    /**
     * @param AutoCompleteTypeLoader $loader
     */
    public function registerTypeLoader(AutoCompleteTypeLoader $loader)
    {
        $this->loader[get_class($loader)] = $loader;
    }

    /**
     * Example:
     *      ... ..  $this->form('MainForm')->title
     *
     * @param $line
     * @param $position
     * @return string
     */
    public function parsePrefix($line, $position)
    {
        $prefix = '';

        for ($i = $position; $i > 0; $i++) {
            $ch = $line[$i];

            if ($ch === '') break;

            if (Str::contains(" \n\t\r", $ch)) break;

            $prefix .= $ch;
        }

        return Str::reverse($prefix);
    }
}