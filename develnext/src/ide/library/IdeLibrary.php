<?php

namespace ide\library;

use Files;
use ide\Ide;
use ide\Logger;
use ide\utils\FileUtils;
use php\io\File;
use php\lang\IllegalArgumentException;
use php\lang\System;
use php\lib\fs;
use php\lib\Str;

/**
 * Class IdeLibrary
 * @package ide\library
 */
class IdeLibrary
{
    private $categories = [
        'projects' => [
            'title' => 'Проекты',
            'type' => 'ide\library\IdeLibraryProjectResource',
        ],
        'quests' => [
            'title' => 'Квесты',
            'type' => 'ide\library\IdeLibraryQuestResource',
        ],
        'bundles' => [
            'title' => 'Пакеты расширений',
            'type' => 'ide\library\IdeLibraryBundleResource',
        ],
        'scriptGenerators' => [
            'title' => 'Генераторы скриптов',
            'type' => 'ide\library\IdeLibraryScriptGeneratorResource'
        ],
        'skins' => [
            'title' => 'Скины',
            'type' => 'ide\library\IdeLibrarySkinResource'
        ]
    ];

    /**
     * @var Ide
     */
    protected $ide;

    /**
     * @var File
     */
    protected $defaultDirectory;

    /**
     * @var File
     */
    protected $directories = [];

    /**
     * @var IdeLibraryResource[]
     */
    protected $resources = [];

    /**
     * IdeLibrary constructor.
     * @param Ide $ide
     */
    public function __construct(Ide $ide)
    {
        $this->ide = $ide;

        $home = System::getProperty('user.home');

        $this->defaultDirectory = File::of("$home/DevelNextLibrary");

        if ($ide->isSnapshotVersion()) {
            $this->defaultDirectory = File::of("$home/DevelNextLibrary.{$ide->getVersionHash()}.SNAPSHOT");
        }

        $this->directories[] = $ide->getOwnFile("library");

        if ($ide->isDevelopment()) {
            $this->directories[] = $ide->getOwnFile("misc/library");
        }

        if (!$this->defaultDirectory->isDirectory()) {
            if (!$this->defaultDirectory->mkdirs()) {
                Logger::error('Cannot create default library directory');
            }
        }
    }

    public function updateCategory($code)
    {
        $directories = $this->directories;
        $directories[] = $this->defaultDirectory;


        if ($type = $this->categories[$code]) {
            $this->resources[$code] = [];

            foreach ($directories as $directory) {
                Logger::info("Scan library resource directory - $directory/$code, type = $type[type]");

                $filter = [
                    'extensions' => ['resource', 'xml', 'zip'],
                    'excludeDirs' => true,
                    'callback' => function ($filename) use ($code, $type) {
                        $path = fs::pathNoExt($filename);

                        Logger::info("Add library ($code) resource $filename, type = $type[type]");

                        /** @var IdeLibraryResource $resource */
                        try {
                            $resource = new $type['type']($path);

                            if (!$resource->isValid()) {
                                return;
                            }

                            $resource->onRegister($this);

                            if ($resource->getUniqueId()) {
                                /** @var IdeLibraryResource $oldResource */
                                $oldResource = $this->resources[$code][$resource->getUniqueId()];

                                if (!$oldResource || $oldResource->isEmbedded()) {
                                    $this->resources[$code][$resource->getUniqueId()] = $resource;
                                }
                            } else {
                                $this->resources[$code][] = $resource;
                            }
                        } catch (\Exception $e) {
                            Logger::exception("Failed to register ($code) resource '$path'", $e);
                        } catch (\Error $e) {
                            Logger::exception("Failed to register ($code) resource '$path'", $e);
                        }
                    }
                ];

                fs::scan("$directory/$code", $filter);
            }
        }
    }

    public function update()
    {
        Logger::info("Update ide library resources ...");

        foreach ($this->categories as $code => $type) {
            $this->updateCategory($code);
        }
    }

    /**
     * @param $category
     * @param $path
     * @return IdeLibraryResource|null
     */
    public function findResource($category, $path)
    {
        /** @var IdeLibraryResource $resource */
        foreach ((array)$this->resources[$category] as $resource) {
            if (FileUtils::equalNames($resource->getPath(), $path)) {
                return $resource;
            }
        }

        return null;
    }

    /**
     * @param string $category
     * @param string $uniqueId
     * @return IdeLibraryResource
     */
    public function getResource($category, $uniqueId)
    {
        $uniqueId = str::lower($uniqueId);
        $uniqueId = str::replace($uniqueId, ' ', '_');
        $uniqueId = str::replace($uniqueId, '-', '_');

        return $this->resources[$category][$uniqueId];
    }

    /**
     * @param $category
     * @return IdeLibraryResource[]
     */
    public function getResources($category)
    {
        return (array)$this->resources[$category];
    }

    /**
     * @param string $category
     * @param string $name
     * @param bool $rewrite
     * @throws IllegalArgumentException
     * @return IdeLibraryResource
     */
    public function makeResource($category, $name, $rewrite = false)
    {
        $info = $this->categories[$category];

        if (!$info) {
            throw new IllegalArgumentException('Invalid category');
        }

        $path = "$this->defaultDirectory/$category/$name";

        if (!$rewrite && fs::exists("$path.resource")) {
            return null;
        } else {
            fs::delete("$path.resource");
            if (fs::isFile($path)) {
                fs::delete("$path");
            } else {
                FileUtils::deleteDirectory("$path");
            }
        }

        return new $info['type']($path);
    }

    /**
     * @param string $category
     * @return string
     */
    public function getResourceDirectory($category)
    {
        $path = "$this->defaultDirectory/$category/";

        if (!fs::isDir($path)) {
            fs::makeDir($path);
        }

        return $path;
    }

    public function delete(IdeLibraryResource $resource)
    {
        $resource->delete();
        $this->updateCategory($resource->getCategory());
    }
}