<?php
namespace ide\webplatform\project\behaviours;


use ide\commands\ExecuteProjectCommand;
use ide\Ide;
use ide\Logger;
use ide\project\Project;
use php\io\File;
use php\lang\Process;
use php\lib\arr;
use php\lib\str;

class WebExecuteProjectCommand extends ExecuteProjectCommand
{
    /**
     * @event run
     */
    protected function doRun()
    {
        Ide::toast("Открытие браузера, подождите ...");

        waitAsync('4s', function () {
            browse("http://localhost:5555/app");
        });
    }

    protected function createExecuteProcess(Project $project): Process
    {
        $classPaths = arr::toList($this->behaviour->getSourceDirectories(), $this->behaviour->getProfileModules(['jar']));

        $args = [
            'java',
            '-cp',
            str::join($classPaths, File::PATH_SEPARATOR),
            '-XX:+UseG1GC', '-Xms128M', '-Xmx512m', '-Dfile.encoding=UTF-8', '-Djphp.trace=true',
            'php.runtime.launcher.Launcher'
        ];

        Logger::debug("Run -> " . str::join($args, ' '));

        return new Process(
            $args,
            $project->getRootDir(),
            Ide::get()->makeEnvironment()
        );
    }
}