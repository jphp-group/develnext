<?php
namespace ide\formats\form\event;
use ide\editors\AbstractEditor;

/**
 * Class AbstractEventKind
 * @package ide\formats\form\event
 */
abstract class AbstractEventKind
{
    /**
     * @var array
     */
    protected $paramVariants = [];

    /**
     * @return array
     */
    abstract public function getArguments();

    /**
     * @param \ide\editors\AbstractEditor $contextEditor
     * @return array
     */
    public function getParamVariants(AbstractEditor $contextEditor = null)
    {
        return $this->paramVariants;
    }

    /**
     * @param array $paramVariants
     */
    public function setParamVariants($paramVariants)
    {
        $this->paramVariants = $paramVariants;
    }

    final public function findParamName($param, AbstractEditor $contextEditor = null)
    {
        $variants = $this->getParamVariants($contextEditor);

        $func = function ($variants) use ($param, &$func) {
            foreach ($variants as $name => $value) {
                if ($value === '-') continue;

                if (is_array($value)) {
                    if ($result = $func($value)) {
                        return $result;
                    }

                    continue;
                }

                if ($value == $param) {
                    return $name;
                }
            }

            return null;
        };

        return $func($variants);
    }

    static function make(array $params)
    {
        $code = $params['code'];
        $name = $params['name'];
        $description = $params['description'];

        $icon = $params['icon'];
        $kind = $params['kind'];

        $kind = "ide\\formats\\form\\event\\{$kind}Kind";

        $kind = new $kind();

        return [
            'code'        => $code,
            'name'        => $name,
            'description' => $description,
            'icon'        => $icon,
            'kind'        => $kind,
        ];
    }
}