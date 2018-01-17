<?php
namespace ide\webplatform\project\behaviours;

use framework\web\UIForm;
use ide\editors\menu\ContextMenu;
use ide\formats\ProjectFormat;
use ide\formats\templates\PhpClassFileTemplate;
use ide\IdeConfiguration;
use ide\Logger;
use ide\project\AbstractProjectBehaviour;
use ide\project\control\CommonProjectControlPane;
use ide\systems\FileSystem;
use ide\webplatform\formats\WebFormFormat;
use php\gui\event\UXEvent;
use php\lib\str;

class WebProjectBehaviour extends AbstractProjectBehaviour
{
    /**
     * ...
     */
    public function inject()
    {
        $this->project->on('open', [$this, 'handleOpen']);

        $this->project->registerFormat($projectFormat = new ProjectFormat());
        $this->project->registerFormat($webFormFormat = new WebFormFormat());

        $projectFormat->addControlPanes([
            new CommonProjectControlPane(),
        ]);

        $addMenu = new ContextMenu();

        FileSystem::setClickOnAddTab(function (UXEvent $e) use ($addMenu) {
            $addMenu->show($e->sender);
        });

        $addMenu->addCommand($webFormFormat->createBlankCommand());
    }

    /**
     * see PRIORITY_* constants
     * @return int
     */
    public function getPriority()
    {
        return self::PRIORITY_LIBRARY;
    }

    public function handleOpen()
    {
        $tree = $this->project->getTree();
        $tree->addIgnoreExtensions([
            'behaviour', 'axml', 'module', 'frm'
        ]);
    }

    public function saveBootstrapScript()
    {
        $template = new GuiLauncherConfFileTemplate();
        $template->setFxSplashAlwaysOnTop($this->splashData['alwaysOnTop']);

        if ($this->splashData['src']) {
            $template->setFxSplash($this->splashData['src']);
        }

        $config = new IdeConfiguration($this->project->getSrcDirectory() . '/JPHP-INF/launcher.conf');
    }

}