<?php
namespace ide\build;

use ide\forms\BuildProgressForm;
use ide\forms\BuildSuccessForm;
use ide\Ide;
use ide\Logger;
use ide\misc\GradleBuildConfig;
use ide\project\behaviours\GradleProjectBehaviour;
use ide\project\Project;
use php\gui\UXApplication;
use php\io\File;
use php\lang\Process;

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
            exclude('.debug/**')
            exclude('**/*.source')

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

        $process = new Process([$ide->getGradleProgram(), 'clean', 'splitConfig', 'jar'], $project->getRootDir(), $ide->makeEnvironment());
        $project->compile(Project::ENV_PROD);

        /** @var GradleProjectBehaviour $gradle */
        $gradle = $project->getBehaviour(GradleProjectBehaviour::class);

        self::appendJarTasks($gradle->getConfig());

        $dialog = new BuildProgressForm();
        $dialog->addConsoleLine('> gradle jar', 'green');
        $dialog->addConsoleLine('   --> ' . $project->getRootDir() . ' ..', 'gray');

        $process = $process->start();

        $dialog->show($process);

        $dialog->setOnExitProcess(function ($exitValue) use ($project, $dialog, $finished, $config) {
            Logger::info("Finish executing: exitValue = $exitValue");

            if ($exitValue == 0) {
                File::of($this->getBuildPath($project) . '/dn-compile-module.jar')->renameTo($this->getBuildPath($project) . "/{$project->getName()}.jar");

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