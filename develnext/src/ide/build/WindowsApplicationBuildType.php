<?php
namespace ide\build;

use ide\commands\BuildProjectCommand;
use ide\formats\templates\Launch4jConfigTemplate;
use ide\forms\BuildProgressForm;
use ide\forms\BuildProjectForm;
use ide\forms\BuildSuccessForm;
use ide\Ide;
use ide\Logger;
use ide\project\behaviours\GradleProjectBehaviour;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\Project;
use ide\systems\ProjectSystem;
use ide\utils\FileUtils;
use php\gui\event\UXEvent;
use php\gui\UXAlert;
use php\gui\UXDialog;
use php\gui\UXFileChooser;
use php\gui\UXImage;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\gui\UXTextArea;
use php\gui\UXTextField;
use php\io\File;
use php\io\FileStream;
use php\io\Stream;
use php\lang\Process;
use php\lib\arr;
use php\lib\fs;
use php\lib\Items;
use php\lib\Str;
use php\xml\XmlProcessor;

/**
 * Class WindowsApplicationBuildType
 * @package ide\build
 */
class WindowsApplicationBuildType extends AbstractBuildType
{
    /**
     * @return string
     */
    function getName()
    {
        return 'Windows Приложение';
    }

    /**
     * @return string
     */
    function getDescription()
    {
        return 'Программа для Windows в виде исполняемого файла (exe)';
    }

    /**
     * @return mixed
     */
    function getIcon()
    {
        return 'icons/windowsOS32.png';
    }

    public function getConfigForm()
    {
        return 'blocks/_WindowsApplicationConfig.fxml';
    }

    public function getLaunch4jConfigPath(Project $project)
    {
        return File::of($this->getBuildPath($project) . '/launch4j.xml')->getCanonicalFile();
    }

    public function getDefaultConfig()
    {
        return [
            'jre'    => true,
            'oneJar' => false,
        ];
    }

    /**
     * @event openButton.action
     *
     * @param UXEvent $event
     */
    public function doOpenButtonClick(UXEvent $event)
    {
        $dialog = new UXFileChooser();
        $dialog->extensionFilters = [
            ['description' => 'Иконки (.ico)', 'extensions' => ['*.ico']]
        ];

        if ($file = $dialog->execute()) {
            /** @var UXTextField $icon */
            $icon = $event->target->scene->window->{'o_exeIcoPath'};

            $localFile = Ide::get()->getOpenedProject()->getIdeDir() . "/" . Str::replace(__CLASS__, '\\', '.') . ".ico";
            FileUtils::copyFile($file, $localFile);

            $icon->text = FileUtils::relativePath(Ide::get()->getOpenedProject()->getRootDir(), $localFile);
        }
    }

    protected function copyJre($project, $cut = true)
    {
        $jreHome = Ide::get()->getJrePath();

        $newJreHome = $this->getBuildPath($project) . '/jre';
        FileUtils::copyDirectory($jreHome, $newJreHome);

        if ($cut) {
            fs::clean("$newJreHome/bin/server");
        }
    }

    /**
     * @param Project $project
     *
     * @return bool|Process
     */
    function makeExecutableFile(Project $project)
    {
        $config = $this->getConfig();

        $jreHome = Ide::get()->getJrePath();
        $launch4j = Ide::get()->getLaunch4JProgram();

        if (!$launch4j) {
            UXDialog::showAndWait('Невозможно собрать исполняемый файл, не найдена утилита Launch4j', 'ERROR');

            return false;
        }

        if ($jreHome && $config['jre']) {
            $alert = new UXAlert('INFORMATION');
            $alert->contentText = 'Копируем Java VM, это может занять некоторое время ...';
            $alert->show();
            $this->copyJre($project);
            $alert->hide();
        }

        $template = new Launch4jConfigTemplate();
        $template->setExeName($project->getName() . '.exe');

        if ($jreHome && $config['jre']) {
            $template->setJrePath('jre');
        }

        $icoFile = File::of($config['exeIcoPath']);

        if ($icoFile->isFile()) {
            $template->setIcoFile($icoFile);
        }

        $icoFile = File::of(Ide::get()->getOpenedProject()->getRootDir() . "/" . $icoFile);

        if ($icoFile->isFile()) {
            $template->setIcoFile($icoFile);
        }

        if ($config['oneJar']) {
            foreach (File::of($this->getBuildPath($project) . '/lib')->findFiles() as $file) {
                if (Str::startsWith($file->getName(), "dn-compile")) {
                    $template->setJarFile('lib/' . $file->getName());
                }
            }
        }

        $configPath = $this->getLaunch4jConfigPath($project);

        $st = new FileStream($configPath, 'w+');
        $template->apply(null, $st);
        $st->close();

        $process = new Process([$launch4j, $configPath], $configPath->getParent(), Ide::get()->makeEnvironment());

        return $process->start();
    }

    /**
     * @param Project $project
     *
     * @param bool $libs
     * @return string
     */
    function getBuildPath(Project $project, $libs = false)
    {
        if ($libs) {
            return $project->getRootDir() . '/build/dist/';
        } else {
            return $project->getRootDir() . '/build/dist/';
        }
    }

    /**
     * @param Project $project
     *
     * @param bool $finished
     *
     * @return mixed
     * @throws \Exception
     */
    function onExecute(Project $project, $finished = true)
    {
        FileUtils::deleteDirectory($this->getBuildPath($project));

        $config = $this->getConfig();

        $config['l4j'] = true;

        $dialog = new BuildProgressForm();
        $dialog->show();

        $jreHome = Ide::get()->getJrePath();
        $launch4j = Ide::get()->getLaunch4JProgram();

        if (!$launch4j) {
            UXDialog::showAndWait('Невозможно собрать исполняемый файл, не найдена утилита Launch4j', 'ERROR');

            return false;
        }

        /*if ($jreHome && $config['jre']) {
            $alert = new UXAlert('INFORMATION');
            $alert->contentText = 'Копируем Java VM, это может занять некоторое время ...';
            $alert->show();
            $this->copyJre($project);
            $alert->hide();
        }*/

        $onExitProcess = function ($exitValue) use ($project, $dialog, $finished) {
            Logger::info("Finish executing: exitValue = $exitValue");

            if ($exitValue == 0) {
                if ($finished) {
                    if (is_callable($finished)) {
                        $finished();

                        return;
                    }

                    $dialog = new BuildSuccessForm();
                    $dialog->setBuildPath($this->getBuildPath($project));
                    $dialog->setOpenDirectory($this->getBuildPath($project));
                    $dialog->setRunProgram("{$this->getBuildPath($project)}/{$project->getName()}.exe");

                    $dialog->showAndWait();
                }
            }
        };
        $dialog->setOnExitProcess($onExitProcess);

        ProjectSystem::compileAll(Project::ENV_PROD, $dialog, 'ant jar launch4j', function ($success) use ($project, $dialog, $config) {
            if ($success) {
                AntOneJarBuildType::makeAntBuildFile($project, $config);

                $args = [Ide::get()->getApacheAntProgram(), $config['oneJar'] ? 'onejar' : 'jar', 'launch4j'];

                if ($config['jre']) {
                    $args[] = 'copy-jre';
                }

                $process = new Process($args, $project->getRootDir(), Ide::get()->makeEnvironment());

                $process = $process->start();

                $dialog->watchProcess($process);
            } else {
                $dialog->stopWithError();
            }
        });
    }
}