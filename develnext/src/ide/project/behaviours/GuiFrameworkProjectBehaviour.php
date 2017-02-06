<?php
namespace ide\project\behaviours;

use develnext\bundle\game2d\Game2DBundle;
use develnext\bundle\jsoup\JsoupBundle;
use develnext\bundle\orientdb\OrientDbBundle;
use Files;
use ide\action\ActionManager;
use ide\build\OneJarBuildType;
use ide\build\SetupWindowsApplicationBuildType;
use ide\build\WindowsApplicationBuildType;
use ide\bundle\std\UIDesktopBundle;
use ide\bundle\std\JPHPDesktopDebugBundle;
use ide\bundle\std\JPHPGuiDesktopBundle;
use ide\commands\BuildProjectCommand;
use ide\commands\CreateFormProjectCommand;
use ide\commands\CreateGameSpriteProjectCommand;
use ide\commands\CreateScriptModuleProjectCommand;
use ide\commands\ExecuteProjectCommand;
use ide\editors\AbstractEditor;
use ide\editors\CodeEditor;
use ide\editors\common\FormListEditor;
use ide\editors\common\ObjectListEditorItem;
use ide\editors\FactoryEditor;
use ide\editors\FormEditor;
use ide\editors\GameSpriteEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\editors\menu\ContextMenu;
use ide\editors\ProjectEditor;
use ide\editors\ScriptModuleEditor;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\elements\GameBackgroundFormElement;
use ide\formats\form\elements\GameObjectFormElement;
use ide\formats\form\elements\GamePaneFormElement;
use ide\formats\form\elements\SpriteViewFormElement;
use ide\formats\form\FormEditorSettings;
use ide\formats\form\FormProjectTreeNavigation;
use ide\formats\GameSpriteFormat;
use ide\formats\GuiFormFormat;
use ide\formats\module\ModuleProjectTreeNavigation;
use ide\formats\ProjectFormat;
use ide\formats\ScriptModuleFormat;
use ide\formats\sprite\IdeSpriteManager;
use ide\formats\sprite\SpriteProjectTreeNavigation;
use ide\formats\templates\GuiApplicationConfFileTemplate;
use ide\formats\templates\GuiBootstrapFileTemplate;
use ide\formats\templates\GuiFormFileTemplate;
use ide\formats\templates\GuiLauncherConfFileTemplate;
use ide\formats\templates\PhpClassFileTemplate;
use ide\Ide;
use ide\Logger;
use ide\project\AbstractProjectBehaviour;
use ide\project\control\CommonProjectControlPane;
use ide\project\control\DesignProjectControlPane;
use ide\project\control\FormsProjectControlPane;
use ide\project\control\ModulesProjectControlPane;
use ide\project\control\SpritesProjectControlPane;
use ide\project\Project;
use ide\project\ProjectExporter;
use ide\project\ProjectFile;
use ide\project\ProjectIndexer;
use ide\project\ProjectTree;
use ide\scripts\ScriptComponentManager;
use ide\systems\FileSystem;
use ide\systems\WatcherSystem;
use ide\utils\FileUtils;
use ide\utils\Json;
use php\gui\event\UXEvent;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXLabel;
use php\gui\UXMenu;
use php\gui\UXMenuItem;
use php\gui\UXNode;
use php\gui\UXTextField;
use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lib\fs;
use php\lib\Str;
use php\util\Configuration;
use php\util\Regex;

class GuiFrameworkProjectBehaviour_ProjectTreeMenuCommand extends AbstractMenuCommand
{
    /**
     * @var UXMenu
     */
    protected $menu;
    /**
     * @var GuiFrameworkProjectBehaviour
     */
    private $gui;

    /**
     * GuiFrameworkProjectBehaviour_ProjectTreeMenuCommand constructor.
     */
    public function __construct(GuiFrameworkProjectBehaviour $gui)
    {
        $this->menu = new UXMenu();
        $this->gui = $gui;
    }

    public function withBeforeSeparator()
    {
        return true;
    }

    public function makeMenuItem()
    {
        $menu = $this->menu;
        $menu->text = $this->getName();
        $menu->graphic = Ide::get()->getImage($this->getIcon());

        return $menu;
    }

    public function getIcon()
    {
        return 'icons/dirs16.png';
    }


    public function getName()
    {
        return "Весь проект";
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {

    }

    /**
     * @param UXMenu|UXMenuItem $item
     * @param AbstractEditor|null $editor
     */
    public function onBeforeShow($item, AbstractEditor $editor = null)
    {
        $menu = $this->menu;
        $menu->items->clear();

        foreach ([$this->gui->getFormEditors(), $this->gui->getModuleEditors(), $this->gui->getSpriteEditors()] as $i => $editors) {
            if ($i > 0 && $editors) {
                $menu->items->add(UXMenuItem::createSeparator());
            }

            /** @var AbstractEditor[] $editors */
            foreach ($editors as $editor) {
                $menuItem = new UXMenuItem($editor->getTitle(), Ide::get()->getImage($editor->getIcon()));
                $menu->items->add($menuItem);

                if (FileSystem::isOpened($editor->getFile())) {
                    $menuItem->style = '-fx-text-fill: blue;';
                }

                $menuItem->on('action', function () use ($editor) {
                    FileSystem::open($editor->getFile());
                });
            }
        }
    }

}

/**
 * Class GuiFrameworkProjectBehaviour
 * @package ide\project\behaviours
 */
class GuiFrameworkProjectBehaviour extends AbstractProjectBehaviour
{
    const FORMS_DIRECTORY = 'src/.forms';
    const FACTORY_DIRECTORY = 'src/.factories';
    const PROTOTYPES_DIRECTORY  = 'src/app/prototypes';
    const SCRIPTS_DIRECTORY = 'src/.scripts';
    const GAME_DIRECTORY = 'src/.game';

    /** @var string */
    protected $mainForm = '';

    /**
     * @var ScriptComponentManager
     */
    protected $scriptComponentManager;

    /**
     * @var ActionManager
     */
    protected $actionManager;

    /**
     * @var IdeSpriteManager
     */
    protected $spriteManager;

    /**
     * @var FormListEditor
     */
    protected $settingsMainFormCombobox;

    /**
     * @var Configuration
     */
    protected $applicationConfig;

    /**
     * @var UXTextField
     */
    protected $uiUuidInput;

    /**
     * @var string app.uuid from application.conf
     */
    protected $appUuid;

    /**
     * @return int
     */
    public function getPriority()
    {
        return self::PRIORITY_LIBRARY;
    }

    /**
     * ...
     */
    public function inject()
    {
        $this->applicationConfig = new Configuration();

        $this->project->on('recover', [$this, 'doRecover']);
        $this->project->on('create', [$this, 'doCreate']);
        $this->project->on('open', [$this, 'doOpen']);
        $this->project->on('save', [$this, 'doSave']);
        $this->project->on('close', [$this, 'doClose']);
        $this->project->on('updateTree', [$this, 'doUpdateTree']);
        $this->project->on('preCompile', [$this, 'doPreCompile']);
        $this->project->on('compile', [$this, 'doCompile']);
        $this->project->on('export', [$this, 'doExport']);
        $this->project->on('reindex', [$this, 'doReindex']);
        $this->project->on('update', [$this, 'doUpdate']);
        $this->project->on('makeSettings', [$this, 'doMakeSettings']);
        $this->project->on('updateSettings', [$this, 'doUpdateSettings']);

        //WatcherSystem::addListener([$this, 'doWatchFile']);

        $this->project->registerFormat($projectFormat = new ProjectFormat());
        $this->project->registerFormat(new GuiFormFormat());
        $this->project->registerFormat(new ScriptModuleFormat());
        $this->project->registerFormat(new GameSpriteFormat());

        $projectFormat->addControlPanes([
            new CommonProjectControlPane(),
            new DesignProjectControlPane(),

            new FormsProjectControlPane(),
            new ModulesProjectControlPane(),
            new SpritesProjectControlPane(),
        ]);

        $addMenu = new ContextMenu();

        FileSystem::setClickOnAddTab(function (UXEvent $e) use ($addMenu) {
            $addMenu->show($e->sender);
        });

        $addMenu->addCommand(new CreateFormProjectCommand());
        $addMenu->addCommand(new CreateScriptModuleProjectCommand());
        $addMenu->addCommand(new CreateGameSpriteProjectCommand());
        $addMenu->add(new GuiFrameworkProjectBehaviour_ProjectTreeMenuCommand($this));

        //Ide::get()->registerCommand(new CreateFormProjectCommand());
        //Ide::get()->registerCommand(new CreateScriptModuleProjectCommand());
        //Ide::get()->registerCommand(new CreateFactoryProjectCommand());

        //Ide::get()->registerCommand(new CreateGameObjectPrototypeProjectCommand());
        //Ide::get()->registerCommand(new CreateGameSpriteProjectCommand());

        Ide::get()->registerSettings(new FormEditorSettings());

        $this->scriptComponentManager = new ScriptComponentManager();
        $this->actionManager = ActionManager::get();
        $this->spriteManager = new IdeSpriteManager($this->project);
    }

    public function makeApplicationConf()
    {
        $this->project->createFile('src/.system/application.conf', new GuiApplicationConfFileTemplate($this->project));
    }

    /**
     * @return string
     */
    public function getAppUuid()
    {
        return $this->appUuid;
    }

    /**
     * @param string $appUuid
     */
    public function setAppUuid($appUuid, $trigger = true)
    {
        $this->appUuid = $appUuid;
        $this->makeApplicationConf();

        if ($trigger) {
            $this->project->trigger('updateSettings');
        }
    }

    public function getMainForm()
    {
        return $this->mainForm;
    }

    public function isMainForm(FormEditor $editor)
    {
        return $this->getMainForm() == $editor->getTitle();
    }

    public function setMainForm($form)
    {
        //Logger::info("Set main form, old = $this->mainForm, new = $form");
        $this->mainForm = $form;

        $this->makeApplicationConf();
    }

    /**
     * @return IdeSpriteManager
     */
    public function getSpriteManager()
    {
        return $this->spriteManager;
    }

    public function doClose()
    {
        $this->actionManager->free();
        $this->scriptComponentManager->free();
        $this->spriteManager->free();
    }

    public function doCreate()
    {
        $this->setAppUuid(str::uuid());


        //FileSystem::open('~project');
    }

    public function doUpdate()
    {
        if ($this->spriteManager) {
            $this->spriteManager->reloadAll();
        }
    }

    public function doUpdateSettings(CommonProjectControlPane $editor = null)
    {
        if ($this->uiUuidInput) {
            $this->uiUuidInput->text = $this->getAppUuid();
        }
    }

    public function doMakeSettings(CommonProjectControlPane $editor)
    {
        $title = new UXLabel('Десктопное приложение:');
        $title->font = $title->font->withBold();

        $uiUuidInput = new UXTextField();
        $uiUuidInput->width = 250;
        $uiUuidInput->editable = false;
        $uiUuidInput->tooltipText = 'В коде можно получить через app()->getUuid(), используется для индетификации приложения в системе.';

        $uiUuid = new UXHBox([new UXLabel('ID приложения'), $uiUuidInput]);
        $uiUuid->spacing = 10;
        $uiUuid->alignment = 'CENTER_LEFT';

        $this->uiUuidInput = $uiUuidInput;
        $uiUuidInput->observer('text')->addListener(function ($old, $new) { $this->setAppUuid($new, false); });

        $wrap = new UXVBox([$title, $uiUuid]);
        $wrap->spacing = 5;

        //$editor->addSettingsPane($wrap);
    }

    public function doReindex(ProjectIndexer $indexer)
    {
        foreach ($this->getFormEditors() as $editor) {
            $editor->reindex();
        }

        foreach ($this->getModuleEditors() as $editor) {
            $editor->reindex();
        }
    }

    public function doExport(ProjectExporter $exporter)
    {
        $exporter->addDirectory($this->project->getFile('src/'));
        $exporter->removeFile($this->project->getFile('src/.debug'));
    }

    public function doPreCompile($env, callable $log = null)
    {
        $withSourceMap = $env == Project::ENV_DEV;

        $this->actionManager->compile($this->project->getSrcFile(''), $this->project->getSrcFile('', true), function ($filename) use ($log) {
            $name = $this->project->getAbsoluteFile($filename)->getRelativePath();

            if ($log) {
                $log(':apply actions "' . $name . '"');
            }
        }, $withSourceMap);
    }

    public function doCompile($environment, callable $log = null)
    {
        $this->updateScriptManager();

        $modules = $this->scriptComponentManager->getModules();
        $values = [];

        foreach ($modules as $module) {
            $values[] = $this->project->getPackageName() . '\\modules\\'
                . Str::replace(FileUtils::relativePath($this->project->getFile(self::SCRIPTS_DIRECTORY), $module), '/', '\\');
        }

        Json::toFile($this->project->getFile('src/.system/modules.json'), [
            'modules' => $values
        ]);
    }

    public function getScriptModules()
    {
        $this->updateScriptManager();

        $modules = $this->scriptComponentManager->getModules();

        foreach ($modules as &$module) {
            $module = Str::replace(FileUtils::relativePath($this->project->getFile(self::SCRIPTS_DIRECTORY), $module), '/', '\\');
        }

        return $modules;
    }

    public function doWatchFile(ProjectFile $file, $event)
    {
        /*if ($file) {
            $path = $file->getRelativePath();

            switch ($event['kind']) {
                case 'ENTRY_CREATE':
                case 'ENTRY_DELETE':
                    if (Str::startsWith($path, self::FORMS_DIRECTORY)) {
                        $this->updateFormsInTree();
                    } else if (Str::startsWith($path, self::SCRIPTS_DIRECTORY)) {
                        $this->updateScriptsInTree();
                        $this->updateScriptManager();
                    }

                    break;
            }
        } */
    }

    public function doSave()
    {
        $this->saveBootstrapScript();
    }

    public function doOpen()
    {
        /*$tree = $this->project->getTree();

        $tree->register(new FormProjectTreeNavigation(self::FORMS_DIRECTORY));
        $tree->register(new ModuleProjectTreeNavigation(self::SCRIPTS_DIRECTORY));
        $tree->register(new SpriteProjectTreeNavigation(self::GAME_DIRECTORY . '/sprites'));

        $tree->getRoot()->root->children->add( $tree->createItemForFile($this->project->getFile('src/.theme/style.css'))->getOrigin() );  */

        /** @var GradleProjectBehaviour $gradleBehavior */
        $gradleBehavior = $this->project->getBehaviour(GradleProjectBehaviour::class);

        $buildConfig = $gradleBehavior->getConfig();

        $buildConfig->addPlugin('application');
        $buildConfig->setDefine('mainClassName', '"php.runtime.launcher.Launcher"');
        $buildConfig->addSourceSet('main.resources.srcDirs', 'src');

        $buildConfig->setDefine('jar.archiveName', '"dn-compiled-module.jar"');

        $this->updateScriptManager();
        $this->updateSpriteManager();

        try {
            $this->applicationConfig->load($this->project->getFile('src/.system/application.conf'));
        } catch (IOException $e) {
            Logger::warn("Unable to load application.conf, {$e->getMessage()}");
        }

        $this->mainForm = $this->applicationConfig->get('app.mainForm', '');
        $this->appUuid = $this->applicationConfig->get('app.uuid', str::uuid());
    }

    public function doUpdateTree(ProjectTree $tree, $path)
    {
        // ... todo.
    }

    public function doRecover()
    {
        if (!$this->project->hasBehaviour(GradleProjectBehavior::class)) {
            $this->project->register(new GradleProjectBehaviour());
        }

        if (!$this->project->hasBehaviour(BundleProjectBehaviour::class)) {
            $this->project->register(new BundleProjectBehaviour());
        }

        $bundle = BundleProjectBehaviour::get();

        if ($bundle) {
            if (!$this->project->getIdeFile("bundles/")->findFiles()) { // for old project formats
                $bundle->addBundle(Project::ENV_ALL, UIDesktopBundle::class, false);
                $bundle->addBundle(Project::ENV_ALL, Game2DBundle::class);
            } else {
                $bundle->addBundle(Project::ENV_ALL, UIDesktopBundle::class, false);
            }

            $bundle->addBundle(Project::ENV_DEV, JPHPDesktopDebugBundle::class, false);
        }

        $this->_recoverDirectories();

        $this->saveBootstrapScript();
        $this->project->defineFile('src/JPHP-INF/launcher.conf', new GuiLauncherConfFileTemplate());
        $this->project->defineFile('src/.system/application.conf', new GuiApplicationConfFileTemplate($this->project));

        // Set config for prototype forms.
        foreach ($this->getFormEditors() as $editor) {
            $usagePrototypes = $editor->getPrototypeUsageList();

            foreach ($usagePrototypes as $factoryId => $ids) {
                $formEditor = $this->getFormEditor($factoryId);

                if (!$formEditor->getConfig()->get('form.withPrototypes')) {
                    $formEditor->getConfig()->set('form.withPrototypes', true);
                    $formEditor->saveConfig();
                }
            }
        }
    }

    public function saveBootstrapScript($incExtension = ['php', 'phb'])
    {
        $template = new GuiBootstrapFileTemplate();

        $code = "";

        Logger::info("Save bootstrap script ...");

        $dirs = [];

        if ($this->project->getSrcGeneratedDirectory()) {
            $dirs[] = $this->project->getSrcFile('.inc', true);
        }

        if ($this->project->getSrcDirectory()) {
            $dirs[] = $this->project->getSrcFile('.inc', false);
        }

        foreach ($dirs as $dir) {
            fs::scan($dir, function ($filename) use (&$code, $dir, $incExtension) {
                $ext = fs::ext($filename);

                if (in_array($ext, $incExtension)) {
                    $name = fs::name(fs::parent($dir));
                    $file = $this->project->getAbsoluteFile($filename);

                    $code .= "include 'res://" . $file->getRelativePath($name) . "'; \n";

                    Logger::debug("Add '{$file->getRelativePath('src')}' to bootstrap script.");
                }
            });
        }

        $template->setInnerCode($code);

        $this->project->defineFile('src/JPHP-INF/.bootstrap', $template, true);

    }

    public function updateScriptManager()
    {
        $this->scriptComponentManager->removeAll();
        $this->scriptComponentManager->updateByPath($this->project->getFile(self::SCRIPTS_DIRECTORY));
    }

    public function updateSpriteManager()
    {
        $this->spriteManager->reloadAll();
    }

    public function recoveryModule($filename)
    {
        $file = $this->project->getAbsoluteFile($filename);

        $sources = $file->findLinkByExtension('php');

        $rel = FileUtils::relativePath($this->project->getFile(self::SCRIPTS_DIRECTORY), $filename);
        $rel = Str::replace($rel, '\\', '/');

        if (!$sources) {
            $sources = $this->project->getFile("src/app/modules/$rel.php");
            $file->addLink($sources);
        }

        if (!$sources->exists()) {
            $this->createModule($rel);
        }
    }

    public function recoveryForm($filename)
    {
        $name = Str::sub($filename, 0, Str::length($filename) - 5);

        $form = $this->project->getAbsoluteFile($filename);
        $conf = $this->project->getAbsoluteFile($name . ".conf");

        $sources = $form->findLinkByExtension('php');

        $rel = FileUtils::relativePath($this->project->getFile(self::FORMS_DIRECTORY), $filename);
        $rel = Str::sub($rel, 0, Str::length($rel) - 5);
        $rel = Str::replace($rel, '\\', '/');

        if (!$sources) {
            $sources = $this->project->getFile("src/app/forms/$rel.php");
            $form->addLink($sources);
        }

        if (!$sources->exists()) {
            Logger::warn("Source file of the '$rel' form not found - $sources, will be create ...");
            $this->createForm($rel);
        }

        if (!$form->findLinkByExtension('conf')) {
            $form->addLink($conf);
        }
    }

    public function makeForm($name)
    {
        $form = $this->project->getFile("src/.forms/$name.fxml");
        $conf = $this->project->getFile("src/.forms/$name.conf");

        $sources = $this->project->getFile("src/app/forms/$name.php");

        if ($sources->exists() && !$form->findLinkByExtension('php')) {
            $form->addLink($sources);
        }

        if (!$form->findLinkByExtension('conf')) {
            $form->addLink($conf);
        }

        return $form;
    }

    public function hasModule($name)
    {
        return $this->project->getFile("src/.scripts/$name")->isDirectory();
    }

    /**
     * @param $name
     * @return ScriptModuleEditor|null
     */
    public function getModuleEditor($name)
    {
        return FileSystem::fetchEditor($this->project->getFile(self::SCRIPTS_DIRECTORY . '/' . $name));
    }

    public function createModule($name)
    {
        if ($this->hasModule($name)) {
            $editor = $this->getModuleEditor($name);
            $editor->delete(true);
        }

        Logger::info("Creating module '$name' ...");

        $this->project->makeDirectory("src/.scripts/$name");

        $file = $this->project->getFile("src/.scripts/$name");

        $template = new PhpClassFileTemplate($name, 'AbstractModule');
        $template->setNamespace("{$this->project->getPackageName()}\\modules");

        $template->setImports([
            'std, gui'
        ]);

        $sources = $file->findLinkByExtension('php');

        if (!$sources) {
            $sources = $this->project->createFile("src/{$this->project->getPackageName()}/modules/$name.php", $template);
            $file->addLink($sources);
        } else {
            if (!$sources->exists()) {
                $sources->applyTemplate($template);
                $sources->updateTemplate(true);
            }
        }

        Logger::info("Finish creating module '$name'");

        return $file;
    }

    /**
     * @return ScriptModuleEditor[]
     */
    public function getModuleEditors()
    {
        $editors = [];
        foreach ($this->getScriptModules() as $file) {
            $editor = FileSystem::fetchEditor($this->project->getFile(self::SCRIPTS_DIRECTORY . '/' . $file), true);
            $editors[FileUtils::hashName($file)] = $editor;
        }

        return $editors;
    }

    /**
     * @return GameSpriteEditor[]
     */
    public function getSpriteEditors()
    {
        $editors = [];

        foreach ($this->spriteManager->getSprites() as $spec) {
            $file = $spec->schemaFile;
            $editor = FileSystem::fetchEditor($file, true);

            if ($editor) {
                $editors[FileUtils::hashName($spec->file)] = $editor;
            } else {
                Logger::error("Unable to find sprite editor for $file");
            }
        }

        return $editors;
    }

    /**
     * @return FormEditor[]
     */
    public function getFormEditors()
    {
        $formDir = $this->project->getFile(self::FORMS_DIRECTORY);

        $editors = [];

        FileUtils::scan($formDir, function ($filename) use (&$editors) {
            if (fs::ext($filename) == "fxml") {
                $editor = FileSystem::fetchEditor($filename, true);

                if ($editor) {
                    $editors[FileUtils::hashName($filename)] = $editor;
                }
            }
        });

        return $editors;
    }

    /**
     * @return FactoryEditor[]
     */
    public function getFactoryEditors()
    {
        $formDir = $this->project->getFile(self::FACTORY_DIRECTORY);

        $editors = [];

        FileUtils::scan($formDir, function ($filename) use (&$editors) {
            if (FileUtils::getExtension($filename) == "factory") {
                $editor = FileSystem::fetchEditor($filename);

                $editors[FileUtils::hashName($filename)] = $editor;
            }
        });

        return $editors;
    }

    /**
     * @param $moduleName
     * @return FormEditor[]
     */
    public function getFormEditorsOfModule($moduleName)
    {
        $formEditors = $this->getFormEditors();

        $result = [];

        foreach ($formEditors as $formEditor) {
            $modules = $formEditor->getModules();

            if ($modules[$moduleName]) {
                $result[FileUtils::hashName($formEditor->getFile())] = $formEditor;
            }
        }

        return $result;
    }

    public function createSprite($name)
    {
        Logger::info("Creating game sprite '$name' ...");

        $file = $this->spriteManager->createSprite($name);

        Logger::info("Finish creating game sprite '$name'");

        return $file;
    }

    public function hasForm($name)
    {
        return $this->project->getFile("src/.forms/$name.fxml")->isFile();
    }

    /**
     * @param $name
     * @return FormEditor|null
     */
    public function getFormEditor($name)
    {
        return $this->hasForm($name) ? FileSystem::fetchEditor($this->project->getFile("src/.forms/$name.fxml"), true) : null;
    }

    public function createForm($name)
    {
        if ($this->hasForm($name)) {
            $editor = $this->getFormEditor($name);
            $editor->delete(true);
        }

        Logger::info("Creating form '$name' ...");

        $form = $this->project->createFile("src/.forms/$name.fxml", new GuiFormFileTemplate());

        $template = new PhpClassFileTemplate($name, 'AbstractForm');
        $template->setNamespace("{$this->project->getPackageName()}\\forms");
        $template->setImports([
            'std, gui'
        ]);

        $sources = $form->findLinkByExtension('php');

        if (!$sources) {
            $sources = $this->project->createFile("src/{$this->project->getPackageName()}/forms/$name.php", $template);
            $form->addLink($sources);
        } else {
            if (!$sources->exists()) {
                $sources->applyTemplate($template);
                $sources->updateTemplate(true);
            }
        }

        $conf = $form->findLinkByExtension('conf');

        if (!$conf) {
            $conf = $this->project->getFile("src/.forms/$name.conf");
            $form->addLink($conf);
        }

        $conf->setHiddenInTree(true);

        Logger::info("Finish creating form '$name'");

        return $form;
    }

    /**
     * @param $id
     * @return array [element => AbstractFormElement, behaviours => [[value, spec], ...]]
     */
    public function getPrototype($id)
    {
        list($group, $id) = str::split($id, '.', 2);

        if ($editor = $this->getFormEditor($group)) {
            $result = [];

            $objects = $this->getObjectList($editor);

            foreach ($objects as $one) {
                if ($one->text == $id) {
                    $result['version'] = $one->version;
                    $result['element'] = $one->element;
                    break;
                }
            }

            $result['behaviours'] = [];

            foreach ($editor->getBehaviourManager()->getBehaviours($id) as $one) {
                $result['behaviours'][] = [
                    'value' => $one,
                    'spec'  => $editor->getBehaviourManager()->getBehaviourSpec($one),
                ];
            }

            return $result;
        }

        return null;
    }

    /**
     * @param AbstractEditor|null $contextEditor
     * @return array
     */
    public function getAllPrototypes(AbstractEditor $contextEditor = null)
    {
        $elements = [];

        foreach ($this->getFormEditors() as $editor) {
            if ($contextEditor && FileUtils::hashName($contextEditor->getFile()) == FileUtils::hashName($editor->getFile())) {
                continue;
            }

            if ($editor->getConfig()->get('form.withPrototypes')) {
                foreach ($editor->getObjectList() as $it) {
                    if ($it->element && $it->element->canBePrototype()) {
                        $it->group = $editor->getTitle();
                        $it->value = "{$it->getGroup()}.{$it->value}";
                        $elements[] = $it;
                    }
                }
            }
        }

        return $elements;
    }

    /**
     * @param $fileName
     * @return ObjectListEditorItem[]
     */
    public function getObjectList($fileName)
    {
        $result = [];
        $project = $this->project;

        $index = $project->getIndexer()->get($this->project->getAbsoluteFile($fileName), '_objects');

        foreach ((array) $index as $it) {
            /** @var AbstractFormElement $element */
            $element = class_exists($it['type']) ? new $it['type']() : null;

            $result[] = $item = new ObjectListEditorItem(
                $it['id'], null
            );

            $item->hint = $element ? $element->getName() : '';
            $item->element = $element;
            $item->version = (int) $it['version'];
            $item->rawType = $it['type'];

            if ($element) {
                if ($graphic = $element->getCustomPreviewImage((array) $it['data'])) {
                    $item->graphic = $graphic;
                } else {
                    $item->graphic = $element->getIcon();
                }
            }
        }

        return $result;
    }

    protected function _recoverDirectories()
    {
        $this->project->makeDirectory('src/');
        $this->project->makeDirectory('src/.data');
        $this->project->makeDirectory('src/.data/img');
        $this->project->makeDirectory('src/.forms');
        $this->project->makeDirectory('src/.system');
        $this->project->makeDirectory('src/.scripts');
        $this->project->makeDirectory('src/.scripts/AppModule');
        $this->project->makeDirectory('src/JPHP-INF');

        $this->project->makeDirectory('src/app');

        $this->project->makeDirectory('src/app/forms');

        $formsPath = $this->project->getFile('src/app/forms');
        $formsPath->setHiddenInTree(true);

        try {
            $modules = Json::fromFile($this->project->getFile("src/.system/modules.json"));

            foreach ((array)$modules["modules"] as $module) {
                $name = str::replace($module, "{$this->project->getPackageName()}\\modules\\", "");
                $this->project->makeDirectory("src/.scripts/$name");
            }
        } catch (IOException $e) {
            ;
        }

        $this->project->getFile(self::FORMS_DIRECTORY)->scan(function ($name, File $file) {
            if ($file->isFile() && Str::endsWith($name, '.fxml')) {
                $this->recoveryForm($file);
            }
        });

        $this->project->getFile('src/.scripts')->scan(function ($name, File $file) {
            if ($file->isDirectory()) {
                $this->recoveryModule($file);
            }
        });
    }

    /**
     * @return ScriptComponentManager
     */
    public function getScriptComponentManager()
    {
        return $this->scriptComponentManager;
    }
}