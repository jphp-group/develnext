<?php
namespace ide\project\behaviours;

use ide\build\OneJarBuildType;
use ide\build\SetupWindowsApplicationBuildType;
use ide\build\WindowsApplicationBuildType;
use ide\commands\BuildProjectCommand;
use ide\commands\ExecuteProjectCommand;
use ide\Ide;
use ide\Logger;
use ide\project\AbstractProjectBehaviour;
use php\io\File;
use php\lib\arr;
use php\lib\fs;
use php\lib\str;
use php\util\Flow;

/**
 * Class RunBuildProjectBehaviour
 * @package ide\project\behaviours
 */
class RunBuildProjectBehaviour extends AbstractProjectBehaviour
{
    /**
     * ...
     */
    public function inject()
    {
        $buildProjectCommand = new BuildProjectCommand();
        $buildProjectCommand->register(new WindowsApplicationBuildType());
        $buildProjectCommand->register(new SetupWindowsApplicationBuildType());
        $buildProjectCommand->register(new OneJarBuildType());

        Ide::get()->registerCommand(new ExecuteProjectCommand($this));
        Ide::get()->registerCommand($buildProjectCommand);
    }


    /**
     * @return array
     */
    public function getSourceDirectories()
    {
        if ($gradle = GradleProjectBehaviour::get()) {
            return arr::toList($gradle->getConfig()->getSourceSets('main.resources.srcDirs'));
        } else {
            return ['src', 'src_generated/'];
        }
    }

    /**
     * @return array
     */
    public function getRepositoryDirectories()
    {
        if ($gradle = GradleProjectBehaviour::get()) {
            $result = $gradle->getConfig()->getDirectoryRepositories();

            foreach ($gradle->getConfig()->getDependencies() as list($group, $artifactId, $version)) {
                if (!$group && !$version && str::startsWith($artifactId, 'dir:')) {
                    $result[] = str::sub($artifactId, 4);
                }
            }

            return $result;
        }

        return [];
    }

    /**
     * @param array $types extensions
     * @return array
     */
    public function getLibraries(array $types)
    {
        $result = [];

        if ($gradle = GradleProjectBehaviour::get()) {
            $repos = $gradle->getConfig()->getDirectoryRepositories();

            foreach ($gradle->getConfig()->getDependencies() as list($group, $artifactId, $version)) {
                if (!$group && !$version) {
                    if (str::startsWith($artifactId, 'dir:')) {
                        $dir = str::sub($artifactId, 4);
                        $directory = new File($this->project->getRootDir() . "/", $dir);

                        foreach ($directory->findFiles() as $one) {
                            if ($one->isFile()) {
                                $result[] = "$dir/{$one->getName()}";
                            }
                        }
                    } else {
                        foreach ($repos as $dir) {
                            if (fs::exists("{$this->project->getRootDir()}/$dir/$artifactId.jar")) {
                                $result[] = "$dir/$artifactId.jar";
                                break;
                            } else {
                                Logger::warn("Cannot find '$dir/$artifactId.jar'");
                            }
                        }
                    }
                }
            }
        }

        $new = [];

        foreach ($result as $one) {
            if (arr::has($types, fs::ext($one))) {
                $new[] = $one;
            }
        }

        return $new;
    }

    /**
     * see PRIORITY_* constants
     * @return int
     */
    public function getPriority()
    {
        return self::PRIORITY_COMPONENT;
    }
}