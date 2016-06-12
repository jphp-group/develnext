<?php
namespace ide\autocomplete;
use develnext\lexer\inspector\AbstractInspector;
use ide\Logger;
use php\io\MemoryStream;
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
    protected $context = null;

    /**
     * @var AutoCompleteTypeRule[]
     */
    protected $rules = [];

    /**
     * @var AutoCompleteTypeLoader[]
     */
    protected $loader = [];

    /**
     * @var AutoCompleteRegion[]
     */
    protected $regions = [];

    /**
     * @var AutoCompleteRegion
     */
    protected $globalRegion = [];

    /**
     * @var AbstractInspector
     */
    private $inspector;

    /**
     * @param AbstractInspector $inspector
     */
    public function __construct(AbstractInspector $inspector)
    {
        $this->inspector = $inspector;
    }

    /**
     * @return AbstractInspector
     */
    public function getInspector()
    {
        return $this->inspector;
    }

    /**
     * @param $sourceCode
     */
    public function update($sourceCode)
    {
        $mem = new MemoryStream();
        $mem->write($sourceCode);

        if ($sourceCode) {
            $mem->seek(0);
        }

        //$tokenizer = new SourceTokenizer($mem, '', 'UTF-8');

        $oldRegions = $this->regions;
        $oldGlobalRegion = $this->globalRegion;

        $this->regions = [];
        $this->globalRegion = new AutoCompleteRegion(0, 0);

        foreach ($this->rules as $rule) {
            if ($rule->updateStart($sourceCode) === false) {
                $this->regions = $oldRegions;
                $this->globalRegion = $oldGlobalRegion;
                return;
            }
        }

        $prev = null;

        /*while ($token = $tokenizer->next()) {
            foreach ($this->rules as $rule) {
                $rule->update($tokenizer, $token, $prev);
            }

            $prev = $token;
        }*/

        foreach ($this->rules as $rule) {
            $rule->updateDone($sourceCode);
        }
    }

    /**
     * @return AutoCompleteRegion
     */
    public function getGlobalRegion()
    {
        return $this->globalRegion;
    }

    public function findRegion($line, $pos) {
        if ($line == 0 && $pos == 0) {
            return $this->globalRegion;
        }

        foreach ($this->regions as $region) {
            if ($region->isAcross($line, $pos)) {
                return $region;
            }
        }

        return $this->globalRegion;
    }

    public function setValueOfRegion($value, $category, $line = 0, $pos = 0)
    {
        if ($region = $this->findRegion($line, $pos)) {
            $region->setValue($value, $category);
        }
    }

    public function addRegion(AutoCompleteRegion $region)
    {
        $this->regions[] = $region;
    }

    /**
     * @param string $prefix
     * @return string[]
     */
    public function identifyType($prefix, AutoCompleteRegion $region)
    {
        $results = [];
        //Logger::debug("Identify type by prefix: $prefix ...");

        if ($prefix) {
            if ($this->context) {
                $result = $this->context->identifyType($prefix, $region);

                if ($result) {
                    if (is_array($result)) {
                        $results += $result;
                    } else {
                        $results[] = $result;
                    }
                }
            }

            foreach ($this->rules as $rule) {
                //Logger::debug("Use rule " . get_class($rule));

                if ($result = $rule->identifyType($prefix, $region)) {
                    if (is_array($result)) {
                        $results += $result;
                    } else {
                        $results[] = $result;
                    }
                }
            }
        }

        //Logger::debug("Identify type by prefix: $prefix = [" . Str::join($results, ', ') . ']');

        return $results;
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