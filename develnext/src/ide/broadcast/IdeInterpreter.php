<?php
namespace ide\broadcast;

use ide\Logger;
use ide\project\Project;
use ide\systems\FileSystem;
use php\lib\str;

/**
 * Class IdeInterpreter
 * @package ide\broadcast
 *
 *   {
 *      cmd: 'openForm',
 *      data: { ... }
 *   }
 *
 */
class IdeInterpreter
{
    /**
     * @var Project
     */
    private $project;


    /**
     * IdeInterpreter constructor.
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function execute(array $command)
    {
        $cmd = str::upperFirst($command['cmd']);

        $method = "command$cmd";

        if (method_exists($this, $method)) {
            call_user_func([$this, $method], (array) $cmd['data']);
        } else {
            Logger::warn("Unknown command '$cmd' to execute it!");
        }
    }

    public function commandOpen(array $data) {
        $file = $data['file'];

        $projectFile = $this->project->getFile($file);

        FileSystem::open($projectFile);
    }
}