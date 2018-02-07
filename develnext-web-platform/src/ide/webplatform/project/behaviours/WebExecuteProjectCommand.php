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
use php\net\ServerSocket;

class WebExecuteProjectCommand extends ExecuteProjectCommand
{
    /**
     * @var int
     */
    protected $webServerPort = 5000;

    /**
     * @event run
     */
    protected function doRun()
    {
        Ide::toast("Открытие браузера, подождите ...");

        waitAsync('4s', function () {
            browse("http://localhost:$this->webServerPort/");
        });
    }

    protected function createExecuteProcess(Project $project): Process
    {
        $classPaths = arr::toList($this->behaviour->getSourceDirectories(), $this->behaviour->getProfileModules(['jar']));

        $port = 5000;
        while (!ServerSocket::isAvailableLocalPort($port)) {
            $port += 1;
        }
        $this->webServerPort = $port;

        $args = [
            'java',
            '-cp',
            str::join($classPaths, File::PATH_SEPARATOR),
            '-XX:+UseG1GC', '-Xms128M', '-Xmx512m', '-Dfile.encoding=UTF-8', '-Djphp.trace=true', "-Dweb.server.port=$port",
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