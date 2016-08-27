<?php
namespace ide\build;

use ide\forms\BuildProgressForm;
use ide\forms\BuildSuccessForm;
use ide\Ide;
use ide\Logger;
use ide\project\behaviours\PhpProjectBehaviour;
use ide\project\Project;
use ide\systems\ProjectSystem;
use ide\utils\FileUtils;
use php\compress\ZipFile;
use php\io\File;
use php\io\IOException;
use php\lang\Process;
use php\lib\fs;
use php\lib\str;
use php\util\Regex;

class AntOneJarBuildType extends AbstractBuildType
{
    /**
     * @return string
     */
    function getName()
    {
        return "JAR Приложение";
    }

    /**
     * @return string
     */
    function getDescription()
    {
        return 'Кроссплатформенное JAR приложение для Linux/Win/MacOS (требует Oracle JRE 1.8+)';
    }

    /**
     * @return mixed
     */
    function getIcon()
    {
        return 'icons/jarFile32.png';
    }

    /**
     * @param Project $project
     *
     * @return string
     */
    function getBuildPath(Project $project)
    {
        return $project->getRootDir() . '/build/dist';
    }

    public static function makeAntBuildFile(Project $project, array $config)
    {
        $content = FileUtils::get('res://ide/build/ant/buildDist.xml');
        $content = str::replace($content, '#NAME#', $project->getName());
        $content = str::replace($content, '#JRE_DIR#', Ide::get()->getJrePath());

        $jarContent = '';
        foreach ([$project->getSrcGeneratedDirectory(), $project->getSrcDirectory()] as $src) {
            $excludes = ".debug/ **/*.source **/*.sourcemap **/*.axml";

            if ($php = PhpProjectBehaviour::get()) {
                if ($php->isByteCodeEnabled()) {
                    $excludes .= " **/*.php";
                }
            }

            $jarContent .= "\t<fileset dir='$src' includes='/*/**' excludes='$excludes'/>\n";
        }

        $content = str::replace($content, '#JAR_CONTENT#', $jarContent);
        $content = str::replace($content, '#DIST_CONTENT#', '');
        $content = str::replace($content, '#LAUNCH4J_DIR#', Ide::get()->getLaunch4JPath());

        if ($config['oneJar']) {
            $content = str::replace($content, '#L4J_JAR_FILE#', '${dist}/' . $project->getName() . '.jar');
            $content = str::replace($content, '#L4J_DONT_WRAP_JAR#', 'false');
        } else {
            $content = str::replace($content, '#L4J_JAR_FILE#', '');
            $content = str::replace($content, '#L4J_DONT_WRAP_JAR#', 'true');
        }

        if ($config['jre']) {
            $content = str::replace($content, '#L4J_JRE_PATH#', 'jre');
        } else {
            $content = str::replace($content, '#L4J_JRE_PATH#', '');
        }

        if ($config['exeIcoPath']) {
            $icoFile = File::of(Ide::get()->getOpenedProject()->getRootDir() . "/" . $config['exeIcoPath']);

            if (!$icoFile->isFile()) {
                $icoFile = File::of($config['exeIcoPath']);
            }

            if ($icoFile->isFile()) {
                $content = str::replace($content, '#L4J_ICON_FILE#', $icoFile);
            } else {
                $content = str::replace($content, 'icon="#L4J_ICON_FILE#"', '');
            }
        } else {
            $content = str::replace($content, 'icon="#L4J_ICON_FILE#"', '');
        }

        if (!$config['l4j']) {
            $content = Regex::of('\\<launch4j\\>.*\\<\\/launch4j\\>', 's')->with($content)->replaceGroup(0, '');
        }



        $project->copyModuleFiles($project->getRootDir() . "/build/dist/lib");

        $extList = '';

        foreach ($project->getModules() as $module) {
            if ($module->getType() == 'jarfile') {
                try {
                    $zipFile = new ZipFile($module->getId());
                    if ($extContent = $zipFile->getEntryContent('META-INF/services/php.runtime.ext.support.Extension')) {
                        $extList .= $extContent . "\n\n";
                    } else {
                        Logger::info("Skip extensions list for module {$module->getId()}");
                    }
                    $zipFile->close();
                } catch (IOException $e) {
                    Logger::warn("Unable to read zip data from {$module->getId()}, {$e->getMessage()}");
                }
            }
        }

        FileUtils::put($project->getRootDir() . "/build.xml", $content);
        FileUtils::put($project->getRootDir() . '/build/dist/gen/META-INF/services/php.runtime.ext.support.Extension', $extList);
    }

    /**
     * @param Project $project
     *
     * @param bool $finished
     *
     * @return mixed
     */
    function onExecute(Project $project, $finished = true)
    {
        FileUtils::deleteDirectory($this->getBuildPath($project));
        $this->makeAntBuildFile($project, $this->getConfig());

        $dialog = new BuildProgressForm();
        $dialog->show();

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

                    $pathToProgram = [Ide::get()->getJrePath() . "/bin/java",  "-jar", "{$this->getBuildPath($project)}/{$project->getName()}.jar"];

                    $dialog->setRunProgram($pathToProgram);

                    $dialog->showAndWait();
                }
            }
        };
        $dialog->setOnExitProcess($onExitProcess);

        ProjectSystem::compileAll(Project::ENV_PROD, $dialog, 'ant onejar', function ($success) use ($project, $dialog) {
            if ($success) {
                $process = new Process([Ide::get()->getApacheAntProgram(), 'onejar'], $project->getRootDir(), Ide::get()->makeEnvironment());

                $process = $process->start();

                $dialog->watchProcess($process);
            } else {
                $dialog->stopWithError();
            }
        });
    }
}