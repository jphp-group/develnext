<?php
namespace ide\project\behaviours;

use ide\Logger;
use ide\project\AbstractProjectBehaviour;
use ide\project\ProjectModule;
use ide\utils\FileUtils;
use php\lib\fs;

class JavaPlatformBehaviour extends AbstractProjectBehaviour
{
    /**
     * ...
     */
    public function inject()
    {
        $this->project->registerModuleTypeHandler('jarfile', [$this, 'jarFileModuleTypeHandler']);
        $this->project->registerModuleTypeHandler('jardir', [$this, 'jarDirModuleTypeHandler']);
    }

    public function jarFileModuleTypeHandler(ProjectModule $module, $one, $remove, $owner, $suffix = '')
    {
        if ($one) {
            $libPath = $this->project->getFile("lib/$suffix");

            $jarFile = $module->getId();
            $name = fs::name($module->getId());
            $file = "{$libPath}/{$name}";

            if (!$remove) {
                if (fs::isFile($jarFile)) {
                    $this->project->loadSourceForInspector($jarFile);

                    /*$size1 = fs::size($jarFile);
                    $size2 = fs::size($file);

                    if ($size1 != $size2 || !fs::isFile($file)) {
                        if (FileUtils::copyFile($jarFile, $file) < 0) {
                            Logger::error("Unable to copy $jarFile to $file");
                        }
                    }*/
                } else {
                    Logger::error("Unable to copy $jarFile");
                }
            } else {
                $this->project->unloadSourceForInspector($jarFile);

                if (fs::isFile($file)) {
                    fs::delete($file);
                }
            }
        }
    }

    public function jarDirModuleTypeHandler(ProjectModule $module, $one, $remove, $owner)
    {
        if ($one) {
            $name = fs::name($module->getId());

            fs::scan($module->getId(), function ($filename) use ($name, $one, $remove, $owner) {
                if (fs::ext($filename) == 'jar') {
                    $this->jarFileModuleTypeHandler(new ProjectModule($filename, 'jarfile'), $one, $remove, $owner, $name);
                }
            }, 1);
        }
    }

    /**
     * see PRIORITY_* constants
     * @return int
     */
    public function getPriority()
    {
        return self::PRIORITY_CORE;
    }
}