<?php
namespace ide\systems;
use ide\forms\MainForm;
use ide\Ide;
use ide\project\AbstractProjectTemplate;
use ide\project\Project;
use ide\utils\FileUtils;
use php\gui\framework\Timer;
use php\io\File;

/**
 * Class ProjectSystem
 * @package ide\systems
 */
class ProjectSystem
{
    protected static function clear()
    {
        WatcherSystem::clear();
        WatcherSystem::clearListeners();
        Ide::get()->unregisterCommands();
    }

    /**
     * @param AbstractProjectTemplate $template
     * @param string $path
     */
    static function create(AbstractProjectTemplate $template, $path)
    {
        static::clear();

        $project = Project::createForFile($path);
        $project->setTemplate($template);

        $template->makeProject($project);
        Ide::get()->setOpenedProject($project);

        $project->create();
        $project->recover();
        $project->open();

        static::save();
    }

    /**
     * @param string $fileName
     */
    static function open($fileName)
    {
        static::clear();
        static::close();

        $file = File::of($fileName);

        $project = new Project($file->getParent(), FileUtils::stripExtension($file->getName()));
        Ide::get()->setOpenedProject($project);

        $project->load();
        $project->recover();
        $project->open();
    }

    /**
     * @throws \Exception
     */
    static function save()
    {
        $project = Ide::get()->getOpenedProject();

        if (!$project) {
            throw new \Exception("Project is not opened");
        }

        $project->save();
    }

    /**
     * Закрывает проект с открытми файлами проекта.
     */
    static function close()
    {
        $project = Ide::get()->getOpenedProject();

        if ($project) {
            $project->close();
        }

        static::clear();

        foreach (FileSystem::getOpened() as $hash => $info) {
            if ($project && $project->isContainsFile($info['file'])) {
                FileSystem::close($info['file']);
            }
        }

        Ide::get()->setOpenedProject(null);
    }
}