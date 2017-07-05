<?php
namespace ide\project\behaviours;

use ide\build\AntOneJarBuildType;
use ide\build\InternalBundleBuildType;
use ide\build\OneJarBuildType;
use ide\build\SetupWindowsApplicationBuildType;
use ide\build\WindowsApplicationBuildType;
use ide\commands\BuildProjectCommand;
use ide\commands\ExecuteProjectCommand;
use ide\Ide;
use ide\Logger;
use ide\project\AbstractProjectBehaviour;
use ide\project\Project;
use ide\utils\FileUtils;
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
        //$buildProjectCommand->register(new OneJarBuildType());
        $buildProjectCommand->register(new AntOneJarBuildType());
        $buildProjectCommand->register(new InternalBundleBuildType());

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
            return ['src_generated/', 'src'];
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
    public function getProfileModules(array $types)
    {
        $result = [];

        if ($project = $this->project) {
            foreach ($project->getModules() as $module) {
                switch ($module->getType()) {
                    default:
                        if (fs::exists($module->getId())) {
                            if (fs::isFile($module->getId())) {
                                $result[] = fs::abs($module->getId());
                            } elseif (fs::isDir($module->getId())) {
                                fs::scan($module->getId(), function ($filename) use ($module, &$result) {
                                    if (fs::name($filename) == fs::nameNoExt($module->getId())) {
                                        return;
                                    }

                                    $result[] = fs::abs($filename);
                                }, 1);
                            }
                        }

                        break;
                }
            }
        }

        $new = [];

        foreach ($result as $one) {
            if (arr::has($types, fs::ext($one))) {
                $new[] = FileUtils::adaptName($one);
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