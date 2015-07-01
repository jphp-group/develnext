<?php
namespace ide\systems;
use ide\Ide;
use ide\project\AbstractProjectTemplate;
use ide\project\Project;
use php\io\File;

/**
 * Class ProjectSystem
 * @package ide\systems
 */
class ProjectSystem
{
    /**
     * @param AbstractProjectTemplate $template
     * @param string $path
     */
    static function create(AbstractProjectTemplate $template, $path)
    {
        WatcherSystem::clear();
        WatcherSystem::clearListeners();

        $project = Project::createForFile($path);

        $template->makeProject($project);
        Ide::get()->setOpenedProject($project);

        $project->create();
        $project->recoverFiles();
        $project->open();
    }

    /**
     * @param string $fileName
     */
    static function open($fileName)
    {
        WatcherSystem::clear();
        WatcherSystem::clearListeners();

        $file = File::of($fileName);

        $project = new Project($file->getPath(), $file->getName());
        $project->load();
        $project->recoverFiles();
        $project->open();

        static::close();

        Ide::get()->setOpenedProject($project);
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

        foreach (FileSystem::getOpened() as $hash => $info) {
            if ($project->isContainsFile($info['file'])) {
                FileSystem::close($info['file']);
            }
        }

        Ide::get()->setOpenedProject($project);
    }
}