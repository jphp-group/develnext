<?php
namespace php\gui\framework;
use Json;
use php\format\ProcessorException;
use php\io\Stream;
use php\lib\Str;
use php\lang\IllegalStateException;
use php\xml\DomDocument;
use php\xml\DomElement;
use php\xml\XmlProcessor;

/**
 * Class ScriptManager
 * @package php\gui\framework
 */
class ScriptManager
{
    /** @var AbstractScript */
    protected $scripts = [];

    public function addConfig($path)
    {
        $json = Json::fromFile($path);

        if (is_array($json['scripts'])) {
            foreach ($json['scripts'] as $script) {
                $type = $script['type'];
                $id = $script['id'];
                $file = $script['file'];
                $handler = $script['handler'];

                /** @var AbstractScript $instance */
                $instance = new $type('res://' . $file);

                if ($handler) {
                    $instance->loadBinds(new $handler());
                }

                $this->add($id, $instance);
            }
        }
    }

    /**
     * @param $name
     * @param AbstractScript $script
     */
    public function add($name, AbstractScript $script)
    {
        $this->scripts[Str::lower($name)] = $script;
    }

    public function __get($name)
    {
        $script = $this->scripts[Str::lower($name)];

        if (!$script) {
            throw new IllegalStateException("Script '$name' not found");
        }

        return $script;
    }
}