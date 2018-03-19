<?php

namespace ide\bundle;

use ide\Ide;
use ide\project\Project;
use ide\project\ProjectModule;
use ide\utils\FileUtils;
use php\compress\ZipFile;
use php\io\File;
use php\io\IOException;
use php\io\MiscStream;
use php\io\Stream;
use php\lib\arr;
use php\lib\fs;
use php\lib\str;
use php\net\URL;
use php\util\Regex;
use php\util\Scanner;

/**
 * Class AbstractJarBundle
 * @package ide\bundle
 */
abstract class AbstractJarBundle extends AbstractBundle
{
    /**
     * @return array
     */
    function getJarDependencies()
    {
        return [];
    }

    /**
     * @return array
     */
    function getProvidedJarDependencies()
    {
        return [];
    }

    /**
     * @return ProjectModule[]
     */
    public function getProjectModules()
    {
        $result = [];

        foreach ($this->getJarDependencies() as $dep) {
            $id = $this->findLibFile($dep);

            if ($id) {
                $result[] = new ProjectModule($id, 'jarfile');
            }
        }

        foreach ($this->getProvidedJarDependencies() as $dep) {
            $id = $this->findLibFile($dep);

            if ($id) {
                $result[] = new ProjectModule($id, 'jarfile', true);
            }
        }

        if ($this->bundleDirectory) {
            fs::scan($this->bundleDirectory, function ($filename) use (&$result) {
                if (fs::ext($filename) == 'jar' && !str::endsWith($filename, '-bundle.jar') && !str::endsWith($filename, '.dn.jar')) {
                    $result[] = new ProjectModule($filename, 'jarfile');
                }
            }, 1);

            //$result[] = new ProjectModule($this->bundleDirectory, 'jardir');
        }

        return $result;
    }

    public function onRemove(Project $project, AbstractBundle $owner = null)
    {
        parent::onRemove($project, $owner);

        $project->removeModule(ProjectModule::ofDir($this->getProjectVendorDirectory()));
    }


    public function onAdd(Project $project, AbstractBundle $owner = null)
    {
        parent::onAdd($project, $owner);

        if ($this->bundleDirectory) {
            $filename = null;

            foreach ((new File($this->bundleDirectory))->findFiles() as $file) {
                if (str::endsWith($file->getName(), '-bundle.jar') || str::endsWith($file->getName(), '.dn.jar')) {
                    $filename = "$file";
                    break;
                }
            }

            if (fs::isFile($filename)) {
                $zipFile = new ZipFile($filename);
                try {
                    $vendorDirectory = $this->getVendorDirectory() . "/";

                    $project->addModule(ProjectModule::ofDir($this->getProjectVendorDirectory()));

                    foreach ($zipFile->statAll() as $stat) {
                        $name = $stat['name'];

                        if ($name == "$vendorDirectory.vendor") {
                            continue;
                        }

                        if (str::startsWith($name, $vendorDirectory)) {
                            if ($stat['directory']) {
                                continue;
                            }

                            $dest = $this->getProjectVendorDirectory()->getPath() . "/" . FileUtils::relativePath($vendorDirectory, $name);

                            FileUtils::copyFileFromZipAsync($filename, $name, $dest, function ($result) use ($project, $dest) {
                                if ($result > -1) {
                                    $project->loadSourceForInspector($dest);
                                }
                            });
                        }
                    }
                } finally {

                }
            }
        }
    }


    /**
     * @return string
     */
    function getDescription()
    {
        return $this->getName() . " JAR Library";
    }

    /**
     * @param Project $project
     * @param string $env
     * @param callable|null $log
     */
    public function onPreCompile(Project $project, $env, callable $log = null)
    {
        parent::onPreCompile($project, $env, $log);

        // todo remove it!
    }

    protected function getSearchLibPaths()
    {
        return [
        ];
    }

    /**
     * @param $name
     * @return null|File
     */
    protected function findLibFile($name)
    {
        if (fs::isFile($name)) {
            return $name;
        }

        return Ide::get()->findLibFile($name, $this->getSearchLibPaths());
    }
}