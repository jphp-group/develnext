<?php
namespace php\gui\framework;
use facade\Json;
use php\format\ProcessorException;
use php\gui\UXApplication;
use php\gui\UXDialog;
use php\io\Stream;
use php\lib\Str;
use php\lang\IllegalStateException;
use php\xml\DomDocument;
use php\xml\DomElement;
use php\xml\XmlProcessor;

/**
 * Class ScriptManager
 * @package php\gui\framework
 * @packages framework
 */
class ScriptManager
{
    /** @var AbstractScript[] */
    protected $scripts = [];

    /**
     * @param string $path
     * @param string $baseUri
     * @param bool $loadScripts
     * @return array
     * @throws IllegalStateException
     */
    public function addFromIndex($path, $baseUri = '', $loadScripts = true)
    {
        $json = Json::fromFile($path);

        $result = [];

        if ($loadScripts) {
            if ($json && $json['scripts']) {
                foreach ($json['scripts'] as $path) {
                    $result[] = $this->addFromConfig($baseUri . $path);
                }
            }
        }

        return $json;
    }

    /**
     * @param string $path to json
     * @return AbstractScript
     * @throws \Exception
     */
    public function addFromConfig($path)
    {
        $json = Json::fromFile($path);

        if ($json) {
            $id = $json['id'];
            $type = $json['type'];

            /** @var AbstractScript $script */
            $script = new $type();
            $this->add($id, $script);

            foreach ((array) $json['properties'] as $key => $value) {
                $script->{$key} = $value;
            }

            $script->{'id'} = $id;

            return $script;
        }

        throw new IllegalStateException("Unable to load script from $path");
    }

    /**
     * @param $name
     * @param AbstractScript $script
     */
    public function add($name, AbstractScript $script)
    {
        $this->scripts[$name] = $script;
    }

    /**
     * @return AbstractScript[]
     */
    public function getScripts()
    {
        return $this->scripts;
    }

    public function __get($name)
    {
        $script = $this->scripts[$name];

        if (!$script) {
            throw new IllegalStateException("Script '$name' not found");
        }

        return $script;
    }

    public function __isset($name)
    {
        return isset($this->scripts[$name]);
    }
}