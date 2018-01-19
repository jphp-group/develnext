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
use ide\webplatform\project\behaviours\web\WebApplicationConfig;
use ide\webplatform\project\behaviours\web\WebBootstrapScriptTemplate;
use ide\webplatform\project\behaviours\web\WebMainUITemplate;
use php\gui\event\UXEvent;
use php\lib\str;

class WebProjectBehaviour extends AbstractProjectBehaviour
{
    /**
     * @var WebBootstrapScriptTemplate
     */
    protected $bootstrapTemplate;

    /**
     * @var WebMainUITemplate
     */
    protected $mainUiTemplate;

    /**
     * @var WebApplicationConfig
     */
    protected $appConfig;


    /**
     * WebProjectBehaviour constructor.
     */
    public function __construct()
    {
        $this->bootstrapTemplate = new WebBootstrapScriptTemplate();
        $this->mainUiTemplate = new WebMainUITemplate();
        $this->appConfig = new WebApplicationConfig();
    }

    /**
     * ...
     */
    public function inject()
    {
        $this->project->on('load', [$this, 'handleLoad']);
        $this->project->on('open', [$this, 'handleOpen']);
        $this->project->on('save', [$this, 'handleSave']);

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

    /**
     * @return WebBootstrapScriptTemplate
     */
    public function getBootstrapTemplate(): ?WebBootstrapScriptTemplate
    {
        return $this->bootstrapTemplate;
    }

    /**
     * @return WebMainUITemplate
     */
    public function getMainUiTemplate(): WebMainUITemplate
    {
        return $this->mainUiTemplate;
    }

    /**
     * @return WebApplicationConfig
     */
    public function getAppConfig(): WebApplicationConfig
    {
        return $this->appConfig;
    }

    public function handleLoad()
    {
        $this->appConfig->useFile($this->project->getSrcFile("application.conf"));
        $this->appConfig->setServerHost('0.0.0.0');
        $this->appConfig->setServerPort(5555);

        $this->mainUiTemplate->useFile($this->project->getSrcFile("{$this->project->getPackageName()}/ui/MainUI.php"));
        $this->mainUiTemplate->setNamespace($this->project->getPackageName() . "\\ui");
        $this->mainUiTemplate->setClassName('MainUI');
        $this->mainUiTemplate->setPath("/" . $this->project->getPackageName());
        $this->mainUiTemplate->setForms(['MainForm' => "{$this->project->getPackageName()}\\forms\\MainForm"]);

        // Use bootstrap php file.
        $this->bootstrapTemplate->useFile($this->project->getSrcFile('JPHP-INF/.bootstrap.php'));
        $this->bootstrapTemplate->setHotDeployEnabled(true);
        $this->bootstrapTemplate->setWatchingDirs([
            './' . $this->project->getSrcDirectory(),
            './' . $this->project->getSrcGeneratedDirectory()
        ]);

        $this->bootstrapTemplate->addUiClass("{$this->project->getPackageName()}\\ui\\MainUI");
    }

    public function handleOpen()
    {
        $tree = $this->project->getTree();
        $tree->addIgnoreExtensions([
            'behaviour', 'axml', 'module', 'frm', 'meta', 'pid'
        ]);
        $tree->addIgnorePaths([
            $this->project->getSrcFile('JPHP-INF/')->getRelativePath(),
            $this->project->getAbsoluteFile($this->appConfig->getFile())->getRelativePath()
        ]);

        $this->bootstrapTemplate->save();
        $this->mainUiTemplate->save();
        $this->appConfig->saveFile();
    }

    public function handleSave()
    {
        $this->bootstrapTemplate->save();
        $this->mainUiTemplate->save();
        $this->appConfig->saveFile();
    }
}