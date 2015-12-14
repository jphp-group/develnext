<?php
namespace ide\build;

use ide\forms\BuildProgressForm;
use ide\forms\BuildSuccessForm;
use ide\Ide;
use ide\Logger;
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

        $process = new Process([$ide->getGradleProgram(), 'clean', 'splitConfig', 'jar', 'doneJar'], $project->getRootDir(), $ide->makeEnvironment());
        $project->compile(Project::ENV_PROD);

        /** @var GradleProjectBehaviour $gradle */
        $gradle = $project->getBehaviour(GradleProjectBehaviour::class);

        $gradle->getConfig()->appendCodeBlock(__CLASS__, "

        task splitConfig << {
             def list = ''<<'';

             configurations.compile.collect {
                if (it.isFile()) {
                    def zip = new java.util.zip.ZipFile(it);

                    zip.entries().each {
                        def name = it.toString().replace(\"\\\\\", \"/\");

                        if (name.endsWith('JPHP-INF/extensions.list')) {
                            list <<= zip.getInputStream(it).text + '\\n';
                        }
                    }
                }
             }

             def file = new File(project.buildDir.path + \"/JPHP-INF/extensions.list\");

             file.getParentFile().mkdirs();

             file << list.toString();
        }

        task doneJar(type: Jar) {
            from (zipTree(jar.destinationDir.path + '/project.jar'))
            from (project.buildDir.path) {
               include 'JPHP-INF/extensions.list'
            }

            doLast {
                new File(project.buildDir.path + \"/JPHP-INF/extensions.list\").delete()
            }

            manifest { attributes 'Main-Class': 'php.runtime.launcher.Launcher' }
        }

        jar {
            exclude('.debug/**')
            exclude('**/*.source')
            exclude('JPHP-INF/extensions.list')

            from configurations.compile.collect {
               it.isDirectory() ? it : zipTree(it)
            }

            manifest { attributes 'Main-Class': 'php.runtime.launcher.Launcher' }

            archiveName = 'project.jar'
        }\n");


        $gradle->getConfig()->save();

        $dialog = new BuildProgressForm();
        $dialog->addConsoleLine('> gradle jar', 'green');
        $dialog->addConsoleLine('   --> ' . $project->getRootDir() . ' ..', 'gray');

        $process = $process->start();

        $dialog->show($process);

        $dialog->setOnExitProcess(function ($exitValue) use ($project, $dialog, $finished, $config) {
            Logger::info("Finish executing: exitValue = $exitValue");

            if ($exitValue == 0) {
                File::of($this->getBuildPath($project) . '/project.jar')->renameTo($this->getBuildPath($project) . "/{$project->getName()}.jar");
                File::of($this->getBuildPath($project) . '/project.jar')->delete();

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