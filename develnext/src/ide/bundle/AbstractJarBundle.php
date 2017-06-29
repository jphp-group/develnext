<?php
namespace ide\bundle;

use ide\Ide;
use ide\Logger;
use ide\project\behaviours\GradleProjectBehaviour;
use ide\project\behaviours\PhpProjectBehaviour;
use ide\project\Project;
use ide\project\ProjectModule;
use ide\utils\FileUtils;
use php\compress\ArchiveEntry;
use php\compress\ArchiveInputStream;
use php\compress\ZipFile;
use php\io\File;
use php\io\IOException;
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
     * @return ProjectModule[]
     */
    public function getProjectModules()
    {
        $result = [];

        foreach ($this->getJarDependencies() as $dep) {
            if (is_array($dep)) {
                $result[] = new ProjectModule(str::split($dep, ':', 4), 'maven');
            } else {
                $id = $this->findLibFile($dep);

                if ($id) {
                    $result[] = new ProjectModule($id, 'jarfile');
                }
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

                    foreach ($zipFile->getEntryNames() as $name) {
                        if ($name == "$vendorDirectory.vendor") {
                            continue;
                        }

                        if (str::startsWith($name, $vendorDirectory)) {
                            $zipEntry = $zipFile->getEntry($name);

                            if ($zipEntry->isDirectory()) {
                                continue;
                            }

                            $this->copyVendorResource(FileUtils::relativePath($vendorDirectory, $name));
                        }
                    }

                } finally {
                    $zipFile->close();
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
            Ide::get()->getOwnFile('lib/')
        ];
    }

    /**
     * @param $name
     * @return null|File
     */
    private function findLibFile($name)
    {
        /** @var File[] $libPaths */
        $libPaths = $this->getSearchLibPaths();

        if (Ide::get()->isDevelopment()) {
            $ownFile = Ide::get()->getOwnFile('build/install/develnext/lib');
            $libPaths[] = $ownFile;
        }

        $regex = Regex::of('(\.[0-9]+|\-[0-9]+)');

        $name = $regex->with($name)->replace('');

        foreach ($libPaths as $libPath) {
            foreach ($libPath->findFiles() as $file) {
                $filename = $regex->with($file->getName())->replace('');

                if (str::endsWith($filename, '.jar') || str::endsWith($filename, '-SNAPSHOT.jar')) {
                    $filename = str::sub($filename, 0, Str::length($filename) - 4);

                    if (str::endsWith($filename, '-SNAPSHOT')) {
                        $filename = Str::sub($filename, 0, Str::length($filename) - 9);
                    }

                    if ($filename == $name) {
                        return $file;
                    }
                }
            }
        }

        return null;
    }
}