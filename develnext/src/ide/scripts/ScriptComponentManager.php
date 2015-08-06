<?php
namespace ide\scripts;

use ide\Ide;
use ide\utils\FileUtils;
use ide\utils\Json;
use php\format\ProcessorException;
use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lib\Str;
use php\xml\DomElement;
use php\xml\XmlProcessor;

/**
 * Class ScriptComponentManager
 * @package ide\scripts
 */
class ScriptComponentManager
{
    /**
     * @var ScriptComponentContainer[]
     */
    protected $components = [];

    /**
     * @param $path
     */
    public function save($path)
    {
        $scripts = [];

        foreach ($this->components as $component) {
            if ($component->getConfigPath() && File::of($component->getConfigPath())->exists()) {
                $scripts[] = [
                    'id' => $component->id,
                    'type' => get_class($component->getType()),
                    'file' => FileUtils::relativePath(File::of($path)->getParent(), $component->getConfigPath()),
                    'properties' => $component->getProperties(),
                ];
            }
        }

        Json::toFile($path, [
            'scripts' => $scripts,
        ]);
    }

    public function saveContainer(ScriptComponentContainer $container)
    {
        Json::toFile(
            $container->getConfigPath(),
            [
                'type' => get_class($container->getType()),
                'properties' => (array)$container->getProperties(),
            ]
        );
    }

    /**
     * @return ScriptComponentContainer[]
     */
    public function getComponents()
    {
        return $this->components;
    }

    /**
     * @param ScriptComponentContainer $container
     */
    public function add(ScriptComponentContainer $container)
    {
        $this->components[FileUtils::hashName($container->getConfigPath())] = $container;
    }

    /**
     * @param ScriptComponentContainer $container
     */
    public function remove(ScriptComponentContainer $container)
    {
        unset($this->components[$container->id]);
    }

    public function removeAll()
    {
        $this->components = [];
    }

    public function updateByPath($pathToScripts)
    {
        FileUtils::scan($pathToScripts, function ($filename) {
            if (Str::endsWith($filename, '.json')) {
                try {
                    $data = Json::fromFile($filename);

                    $id = FileUtils::stripExtension(File::of($filename)->getName());
                    $type = $data['type'];

                    if ($id && $type) {
                        $component = new ScriptComponentContainer(new $type, $id);
                        $component->setConfigPath($filename);

                        if (is_array($data['properties'])) {
                            foreach ($data['properties'] as $key => $value) {
                                $component->{$key} = $value;
                            }
                        }

                        $this->add($component);
                    }

                } catch (ProcessorException $e) {
                    ;
                } catch (IOException $e) {
                    ;
                }
            }
        });
    }
}