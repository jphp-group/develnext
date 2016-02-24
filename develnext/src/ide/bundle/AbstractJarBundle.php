<?php
namespace ide\bundle;

use ide\Ide;
use ide\project\behaviours\GradleProjectBehaviour;
use ide\project\Project;
use ide\utils\FileUtils;
use php\io\File;
use php\lib\str;
use php\util\Regex;

/**
 * Class AbstractJarBundle
 * @package ide\bundle
 */
abstract class AbstractJarBundle extends AbstractBundle
{
    /**
     * @return array
     */
    abstract function getJarDependencies();

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
        $libPath = $project->getFile('lib/');

        foreach ($this->getJarDependencies() as $dep) {
            if (!is_array($dep)) {
                $jarFile = $this->findLibFile($dep);

                if ($jarFile) {
                    FileUtils::copyFile($jarFile, "$libPath/$dep.jar");
                }
            }
        }
    }

    /**
     * @param GradleProjectBehaviour $gradle
     */
    public function applyForGradle(GradleProjectBehaviour $gradle)
    {
        foreach ($this->getJarDependencies() as $dep) {
            if (is_array($dep)) {
                $gradle->addDependency($dep[1], $dep[0], $dep[2]);
            } else {
                $gradle->addDependency($dep);
            }
        }
    }

    protected function getSearchLibPaths()
    {
        return [
            Ide::get()->getOwnFile('lib/')
        ];
    }

    private function findLibFile($name)
    {
        /** @var File[] $libPaths */
        $libPaths = $this->getSearchLibPaths();

        if (Ide::get()->isDevelopment()) {
            $ownFile = Ide::get()->getOwnFile('build/install/develnext/lib');
            $libPaths[] = $ownFile;
        }

        $regex = Regex::of('(\.[0-9]|\-[0-9])');

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