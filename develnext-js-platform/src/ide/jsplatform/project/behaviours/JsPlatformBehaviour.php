<?php
namespace ide\jsplatform\project\behaviours;

use ide\Ide;
use ide\jsplatform\formats\JavaScriptCodeFormat;
use ide\project\AbstractProjectBehaviour;
use php\lang\Process;

class JsPlatformBehaviour extends AbstractProjectBehaviour
{
    /**
     * @var Process
     */
    protected $httpServer;

    /**
     * ...
     */
    public function inject()
    {
        $this->project->on('open', [$this, 'doOpen']);
        $this->project->on('close', [$this, 'doClose']);

        $this->project->registerFormat(new JavaScriptCodeFormat());
    }

    public function doOpen()
    {
        $toolManager = Ide::get()->getToolManager();

        $toolManager->install(['node', 'npm', 'gulp', 'electron', 'http-server'], function () use ($toolManager) {
            if (!$this->project->getFile('package.json')->exists()) {
                $toolManager->execute('npm', ['init', '-y'], $this->project->getFile(''))->startAndWait();
            }

            //$this->httpServer = $toolManager->execute('http-server', [$this->project->getFile('src')->getAbsolutePath(), '-p', '3434'])->start();
        });
    }

    public function doClose()
    {
        /*if ($this->httpServer->getExitValue() === null) {
            $this->httpServer->destroy();
        } */
    }

    /**
     * see PRIORITY_* constants
     * @return int
     */
    public function getPriority()
    {
        return self::PRIORITY_CORE;
    }
}