<?php
namespace ide\build;

use ide\forms\BuildProgressForm;
use ide\forms\BuildSuccessForm;
use ide\Ide;
use ide\Logger;
use ide\misc\GradleBuildConfig;
use ide\project\behaviours\GradleProjectBehaviour;
use ide\project\Project;
use ide\systems\ProjectSystem;
use ide\ui\Notifications;
use php\gui\UXApplication;
use php\io\File;
use php\lang\Process;
use script\TimerScript;

/**
 * Class OneJarBuildType
 * @package ide\build
 */
class OneJarBuildType extends AbstractBuildType
{
    /**
     * @return string
     */
    function getName()
    {
        return 'JAR Приложение';
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

    function getBuildPath(Project $project)
    {
        return $project->getRootDir() . '/build/libs';
    }

    static function appendJarTasks(GradleBuildConfig $config, $oneJar = true)
    {
        $code = '';

        if ($oneJar) {
            $code = "
                from (configurations.compile.collect { it.isDirectory() ? it : zipTree(it) }) {
                    exclude('JPHP-INF/extensions.list')
                    exclude('META-INF/services/php.runtime.ext.support.Extension')
                }

                from 'src/META-INF/services/php.runtime.ext.support.Extension'

                manifest { attributes 'Main-Class': 'php.runtime.launcher.Launcher' }
            ";
        }

        $config->appendCodeBlock(__CLASS__, "

        task splitConfig << {
             def list = ''<<'';

             configurations.compile.collect {
                if (it.isFile()) {
                    def zip = new java.util.zip.ZipFile(it);

                    zip.entries().each {
                        def name = it.toString().replace(\"\\\\\", \"/\");

                        if (name.endsWith('META-INF/services/php.runtime.ext.support.Extension')) {
                            list <<= zip.getInputStream(it).text + '\\n';
                        }
                    }
                }
             }

             def file = new File(\"src/META-INF/services/php.runtime.ext.support.Extension\");
             file.getParentFile().mkdirs();

             file << list.toString();
        }

        jar {
            encoding = 'UTF-8'

            exclude('.debug/**')
            exclude('**/*.source')
            exclude('**/*.sourcemap')
            exclude('**/*.axml')
            exclude('JPHP-INF/sdk/**')

            $code
        }\n");

        $config->save();
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
        Logger::info("Start executing ...");

        $ide = Ide::get();

        $config = $this->getConfig();

        $dialog = new BuildProgressForm();
        $dialog->show();

        ProjectSystem::compileAll(Project::ENV_PROD, $dialog, 'gradle jar', function () use ($ide, $project, $dialog) {
            $process = new Process([$ide->getGradleProgram(), 'clean', 'splitConfig', 'jar'], $project->getRootDir(), $ide->makeEnvironment());

            /** @var GradleProjectBehaviour $gradle */
            $gradle = $project->getBehaviour(GradleProjectBehaviour::class);

            self::appendJarTasks($gradle->getConfig());

            $process = $process->start();

            $dialog->watchProcess($process);
        });

        $dialog->setOnExitProcess(function ($exitValue) use ($project, $dialog, $finished, $config) {
            Logger::info("Finish executing: exitValue = $exitValue");

            if ($exitValue == 0) {
                $path = $this->getBuildPath($project) . '/dn-compiled-module.jar';
                $newPath = $this->getBuildPath($project) . "/{$project->getName()}.jar";

                $done = File::of($path)->renameTo($newPath);

                if (!$done) {
                    Logger::warn("Unable to rename $path -> $newPath");
                    Notifications::warning('Необольшое недоразумение', 'Корректно сформировать имя программы после сборки не вышло, вы можете сами переименовать её!');
                }

                if ($finished) {
                    if (is_callable($finished)) {
                        $finished();

                        return;
                    }

                    $dialog = new BuildSuccessForm();
                    $dialog->setBuildPath($this->getBuildPath($project));
                    $dialog->setOpenDirectory($this->getBuildPath($project));

                    $pathToProgram = Ide::get()->getJrePath() . "/bin/java -jar {$this->getBuildPath($project)}/{$project->getName()}.jar";

                    $dialog->setRunProgram($pathToProgram);

                    $dialog->showAndWait();
                }
            }
        });
    }
}