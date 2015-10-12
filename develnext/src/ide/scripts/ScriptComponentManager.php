<?php
namespace ide\scripts;

use ide\Ide;
use ide\utils\FileUtils;
use ide\utils\Json;
use php\format\ProcessorException;
use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lang\IllegalStateException;
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
     * @var File[]
     */
    protected $modules = [];

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

    public function loadContainer($path)
    {
        $json = Json::fromFile($path);

        if ($json) {
            $type = $json['ideType'];

            if ($type) {
                $type = new $type();
                $container = new ScriptComponentContainer($type, FileUtils::stripExtension(File::of($path)->getName()));
                $container->setConfigPath($path);

                $container->setX((int)$json['x']);
                $container->setY((int)$json['y']);

                foreach ((array)$json['properties'] as $key => $value) {
                    $container->__set($key, $value);
                }

                return $container;
            }
        }

        return null;
    }

    public function saveContainer(ScriptComponentContainer $container)
    {
        Json::toFile(
            $container->getConfigPath(),
            [
                'id' => $container->id,
                'ideType' => get_class($container->getType()),
                'type' => $container->getType()->getType(),
                'x' => $container->getX(),
                'y' => $container->getY(),
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
     * @param $id
     * @return ScriptComponentContainer|null
     */
    public function findById($id)
    {
        foreach ($this->components as $component) {
            if ($component->id == $id) {
                return $component;
            }
        }

        return null;
    }

    /**
     * @param ScriptComponentContainer $container
     */
    public function remove(ScriptComponentContainer $container)
    {
        unset($this->components[FileUtils::hashName($container->getConfigPath())]);
    }

    /**
     * @return File[]
     */
    public function getModules()
    {
        return $this->modules;
    }

    public function removeAll()
    {
        $this->modules = [];
        $this->components = [];
    }

    public function updateByPath($pathToScripts)
    {
        FileUtils::scan($pathToScripts, function ($filename) {
            $file = File::of($filename);

            if ($file->isDirectory()) {
                $this->modules[FileUtils::hashName($filename)] = $file;
                return;
            }

            if (Str::endsWith($filename, '.json')) {
                try {
                    $data = Json::fromFile($filename);

                    $id = FileUtils::stripExtension(File::of($filename)->getName());
                    $type = $data['ideType'];

                    $x = (int)$data['x'];
                    $y = (int)$data['y'];

                    if ($id && $type) {
                        $component = new ScriptComponentContainer(new $type, $id);
                        $component->setConfigPath($filename);
                        $component->setX($x);
                        $component->setY($y);

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

    public function renameId(ScriptComponentContainer $container, $newId)
    {
        $newConfigPath = File::of($container->getConfigPath())->getParent() . "/$newId.json";

        if (File::of($container->getConfigPath())->renameTo($newConfigPath)) {
            $this->remove($container);
            $container->setConfigPath($newConfigPath);
            $container->id = $newId;
            $this->add($container);

            return true;
        }

        //throw new IllegalStateException("Unable to rename {$container->getConfigPath()} to $newConfigPath");

        return false;
    }
}