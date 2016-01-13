<?php
namespace ide\systems;
use ide\forms\MainForm;
use ide\forms\MessageBoxForm;
use ide\forms\OpenProjectForm;
use ide\Ide;
use ide\Logger;
use ide\project\AbstractProjectTemplate;
use ide\project\Project;
use ide\project\ProjectImporter;
use ide\utils\FileUtils;
use php\gui\framework\Timer;
use php\gui\UXApplication;
use php\gui\UXDialog;
use php\gui\UXDirectoryChooser;
use php\io\File;
use php\lib\Items;
use php\lib\Str;
use script\TimerScript;

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

    static public function checkDirectory($path)
    {
        Logger::info("Check directory: $path");

        if (File::of($path)->find()) {
            $msg = new MessageBoxForm("Папка $path для проекта должна быть пустой, хотите очистить её, чтобы продолжить?", [
                'Да, очистить и продолжить',
                'Нет, выбрать другую',
                'Отмена'
            ]);

            if ($msg->showDialog()) {
                switch ($msg->getResultIndex()) {
                    case 0:
                        FileUtils::deleteDirectory($path);
                        break;
                    case 1:
                        $dialog = new UXDirectoryChooser();
                        $dialog->initialDirectory = $path;

                        if ($file = $dialog->showDialog()) {
                            return $file;
                        } else {
                            return null;
                        }

                        break;
                    case 2:
                        return null;
                }
            }
        }

        return $path;
    }

    static function import($file, $projectDir = null, $newName = null, callable $afterOpen = null)
    {
        Logger::info("Start import project: file = $file, projectDir = $projectDir");

        Ide::get()->getMainForm()->showPreloader('Распаковка архива ...');

        ProjectSystem::close();

        if (!$projectDir) {
            $projectDir = FileUtils::stripExtension($file);
        }

        if (!($projectDir = self::checkDirectory($projectDir))) {
            Ide::get()->getMainForm()->hidePreloader();
            return;
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

        $file = File::of(Items::first($files));

        if ($newName) {
            $files = [File::of($file->getParent() . "/$newName.dnproject")];

            $file->renameTo(Items::first($files));
        }

        TimerScript::executeAfter(1000, function () use ($projectDir, $files, $file, $afterOpen) {
            ProjectSystem::open($projectDir . "/" . Items::first($files)->getName());

            Ide::get()->getMainForm()->toast("Проект был успешно импортирован из архива", 3000);

            Logger::info("Finish importing project.");

            if ($afterOpen) {
                $afterOpen();
            }
        });
    }

    /**
     * @param AbstractProjectTemplate $template
     * @param string $path
     */
    static function create(AbstractProjectTemplate $template, $path)
    {
        static::clear();

        $parent = File::of($path)->getParent();

        if (!($parent = self::checkDirectory($parent))) {
            FileSystem::open('~welcome');
            return;
        }

        $path = $parent . "/" . File::of($parent)->getName();

        $project = Project::createForFile($path);
        $project->setTemplate($template);

        $template->makeProject($project);
        Ide::get()->setOpenedProject($project);

        $project->create();
        $project->recover();
        $project->open();

        Ide::get()->trigger('openProject', [$project]);

        static::save();
    }

    /**
     * @param string $fileName
     * @param bool $showDialogAlreadyOpened
     */
    static function open($fileName, $showDialogAlreadyOpened = true)
    {
        Logger::info("Start opening project: $fileName");

        Ide::get()->getMainForm()->showPreloader('Открытие проекта ...');

        static::clear();
        static::close();

        $file = File::of($fileName);

        $project = new Project($file->getParent(), FileUtils::stripExtension($file->getName()));

        if ($project->isOpenedInOtherIde()) {
            if ($showDialogAlreadyOpened) {
                $msg = new MessageBoxForm('Данный проект уже открыт в другом экземпляре среды!', ['ОК, открыть другой проект', 'Выход']);
                $msg->showDialog();

                if ($msg->getResultIndex() == 0) {
                    uiLater(function () {
                        $dialog = new OpenProjectForm();
                        $dialog->showDialog();
                    });
                }
            }

            FileSystem::open('~welcome');
            Ide::get()->getMainForm()->hidePreloader();
            return;
        }

        Ide::get()->setOpenedProject($project);

        FileSystem::open('~project');

        $project->load();
        $project->recover();
        $project->open();

        Ide::get()->getMainForm()->hidePreloader();

        Ide::get()->trigger('openProject', [$project]);

        Logger::info("Finish opening project.");
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

    static function closeWithWelcome()
    {
        self::close();
        FileSystem::open('~welcome');
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

        if ($project) {
            Ide::get()->trigger('closeProject', [$project]);
        }
    }
}