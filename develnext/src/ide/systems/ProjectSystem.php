<?php
namespace ide\systems;
use ide\forms\MainForm;
use ide\Ide;
use ide\project\AbstractProjectTemplate;
use ide\project\Project;
use ide\project\ProjectImporter;
use ide\utils\FileUtils;
use php\gui\framework\Timer;
use php\gui\UXApplication;
use php\gui\UXDialog;
use php\io\File;
use php\lib\Items;
use php\lib\Str;

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

    static function import($file, $projectDir = null)
    {
        Ide::get()->getMainForm()->showPreloader('Распаковка архива ...');

        ProjectSystem::close();

        if (!$projectDir) {
            $projectDir = FileUtils::stripExtension($file);
        }

        FileUtils::deleteDirectory($projectDir);

        $importer = new ProjectImporter($file);

        $importer->extract($projectDir);

        $files = File::of($projectDir)->findFiles(function (File $dir, $name) {
            return (Str::endsWith($name, '.dnproject'));
        });

        if (!$files) {
            UXDialog::show('В архиве не обнаружен файл проекта', 'ERROR');
            return;
        }

        ProjectSystem::open($projectDir . "/" . Items::first($files)->getName());

        Ide::get()->getMainForm()->toast("Проект был успешно импортирован из архива \n -> $file");
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
        Ide::get()->getMainForm()->showPreloader('Открытие проекта ...');

        static::clear();
        static::close();

        $file = File::of($fileName);

        $project = new Project($file->getParent(), FileUtils::stripExtension($file->getName()));
        Ide::get()->setOpenedProject($project);

        $project->load();
        $project->recover();
        $project->open();

        Ide::get()->getMainForm()->hidePreloader();
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

        /** @var MainForm $mainForm */
        $mainForm = Ide::get()->getMainForm();
        $pane = $mainForm->getPropertiesPane();

        $pane->children->clear();

        static::clear();

        foreach (FileSystem::getOpened() as $hash => $info) {
            //if ($project && $project->isContainsFile($info['file'])) {
                FileSystem::close($info['file']);
            //}
        }

        Ide::get()->setOpenedProject(null);
    }
}