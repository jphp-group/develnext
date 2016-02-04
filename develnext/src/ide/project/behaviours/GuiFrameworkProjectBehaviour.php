<?php
namespace ide\project\behaviours;

use Files;
use ide\action\ActionManager;
use ide\build\OneJarBuildType;
use ide\build\SetupWindowsApplicationBuildType;
use ide\build\WindowsApplicationBuildType;
use ide\commands\BuildProjectCommand;
use ide\commands\CreateFactoryProjectCommand;
use ide\commands\CreateFormProjectCommand;
use ide\commands\CreateGameObjectPrototypeProjectCommand;
use ide\commands\CreateGameSceneProjectCommand;
use ide\commands\CreateGameSpriteProjectCommand;
use ide\commands\CreateScriptModuleProjectCommand;
use ide\commands\ExecuteProjectCommand;
use ide\editors\AbstractEditor;
use ide\editors\common\FormListEditor;
use ide\editors\common\ObjectListEditorItem;
use ide\editors\FactoryEditor;
use ide\editors\FormEditor;
use ide\editors\ProjectEditor;
use ide\editors\ScriptModuleEditor;
use ide\formats\factory\FactoryProjectTreeNavigation;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\FormProjectTreeNavigation;
use ide\formats\module\ModuleProjectTreeNavigation;
use ide\formats\ScriptFormat;
use ide\formats\sprite\IdeSpriteManager;
use ide\formats\sprite\SpriteProjectTreeNavigation;
use ide\formats\templates\GuiApplicationConfFileTemplate;
use ide\formats\templates\GuiBootstrapFileTemplate;
use ide\formats\templates\GuiFormFileTemplate;
use ide\formats\templates\PhpClassFileTemplate;
use ide\Ide;
use ide\Logger;
use ide\project\AbstractProjectBehaviour;
use ide\project\Project;
use ide\project\ProjectExporter;
use ide\project\ProjectFile;
use ide\project\ProjectIndexer;
use ide\project\ProjectTree;
use ide\project\ProjectTreeItem;
use ide\scripts\elements\TimerScriptComponent;
use ide\scripts\ScriptComponentManager;
use ide\systems\FileSystem;
use ide\systems\WatcherSystem;
use ide\utils\FileUtils;
use ide\utils\Json;
use php\gui\framework\Timer;
use php\gui\layout\UXHBox;
use php\gui\UXLabel;
use php\gui\UXTreeItem;
use php\gui\UXTreeView;
use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lib\Str;
use php\util\Configuration;
use php\util\Regex;

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
    protected $mainForm = 'MainForm';

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
     * ...
     */
    public function inject()
    {
        $this->applicationConfig = new Configuration();

        $this->project->on('recover', [$this, 'doRecover']);
        $this->project->on('create', [$this, 'doCreate']);
        $this->project->on('open', [$this, 'doOpen']);
        $this->project->on('updateTree', [$this, 'doUpdateTree']);
        $this->project->on('compile', [$this, 'doCompile']);
        $this->project->on('export', [$this, 'doExport']);
        $this->project->on('reindex', [$this, 'doReindex']);
        $this->project->on('update', [$this, 'doUpdate']);
        $this->project->on('makeSettings', [$this, 'doMakeSettings']);
        $this->project->on('updateSettings', [$this, 'doUpdateSettings']);

        WatcherSystem::addListener([$this, 'doWatchFile']);

        $buildProjectCommand = new BuildProjectCommand();
        $buildProjectCommand->register(new WindowsApplicationBuildType());
        $buildProjectCommand->register(new SetupWindowsApplicationBuildType());
        $buildProjectCommand->register(new OneJarBuildType());

        Ide::get()->registerCommand($buildProjectCommand);
        Ide::get()->registerCommand(new ExecuteProjectCommand());
        Ide::get()->registerCommand(new CreateFormProjectCommand());
        Ide::get()->registerCommand(new CreateScriptModuleProjectCommand());
        //Ide::get()->registerCommand(new CreateFactoryProjectCommand());

        //Ide::get()->registerCommand(new CreateGameObjectPrototypeProjectCommand());
        Ide::get()->registerCommand(new CreateGameSpriteProjectCommand());

        $this->scriptComponentManager = new ScriptComponentManager();
        $this->actionManager = new ActionManager();
        $this->spriteManager = new IdeSpriteManager($this->project);
    }

    public function getMainForm()
    {
        return $this->mainForm;
    }

    public function setMainForm($form, $trigger = true)
    {
        Logger::info("Set main form, old = $this->mainForm, new = $form");
        $this->mainForm = $form;

        $this->project->createFile('src/.system/application.conf', new GuiApplicationConfFileTemplate($this->project));

        if ($trigger) {
            $this->project->trigger('updateSettings');
        }
    }

    /**
     * @return IdeSpriteManager
     */
    public function getSpriteManager()
    {
        return $this->spriteManager;
    }

    public function doCreate()
    {
        $appModule = $this->createModule('AppModule');
        FileSystem::open($appModule);

        $mainModule = $this->createModule('MainModule');
        FileSystem::open($mainModule);

        $mainForm = $this->createForm($this->mainForm);
        FileSystem::open($mainForm);

    }

    public function doUpdate()
    {
        if ($this->spriteManager) {
            $this->spriteManager->reloadAll();
        }
    }

    public function doUpdateSettings(ProjectEditor $editor = null)
    {
        if ($this->settingsMainFormCombobox) {
            $mainForm = $this->getMainForm();

            $this->settingsMainFormCombobox->updateUi();

            $this->setMainForm($mainForm, false);
            $this->settingsMainFormCombobox->setSelected($mainForm);
        }
    }

    public function doMakeSettings(ProjectEditor $editor)
    {
        $formListEditor = new FormListEditor();
        $formListEditor->setEmptyItemText('[Нет]');
        $formListEditor->build();

        $formListEditor->onChange(function ($value) {
            $this->setMainForm($value, false);
        });

        $formListEditor->getUi()->width = 250;

        $ui = new UXHBox([
            new UXLabel('Главная форма'),
            $formListEditor->getUi()
        ]);
        $ui->spacing = 10;
        $ui->alignment = 'CENTER_LEFT';

        $this->settingsMainFormCombobox = $formListEditor;

        $editor->addSettingsPane($ui);
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

        // remove .php files if .source exists
        /*FileUtils::scan($this->project->getFile('src'), function ($filename) use ($exporter) {
            if (Str::endsWith($filename, '.php') && Files::exists($filename . '.source')) {
                $exporter->removeFile($filename);
            }
        });*/
    }

    public function doCompile($environment, callable $log = null) {
        /** @var GradleProjectBehaviour $gradleBehavior */
        $gradleBehavior = $this->project->getBehaviour(GradleProjectBehaviour::class);

        $buildConfig = $gradleBehavior->getConfig();

        $buildConfig->addRepository('jcenter');
        $buildConfig->addRepository('mavenCentral');
        $buildConfig->addRepository('local', new File("lib/"));

        $buildConfig->setDependency('asm-all');
        $buildConfig->setDependency('jphp-runtime');
        $buildConfig->setDependency('jphp-core');
        $buildConfig->setDependency('gson');
        $buildConfig->setDependency('jphp-json-ext');
        $buildConfig->setDependency('jphp-xml-ext');
        $buildConfig->setDependency('jphp-gui-ext');
        $buildConfig->setDependency('jphp-gui-desktop-ext');
        $buildConfig->setDependency('jphp-game-ext');
        $buildConfig->setDependency('dyn4j');
        $buildConfig->setDependency('jphp-zend-ext');
        $buildConfig->setDependency('jphp-gui-framework');
        $buildConfig->setDependency('develnext-stdlib');

        foreach ($this->scriptComponentManager->getComponents() as $component) {
            $component->getType()->adaptForGradleBuild($buildConfig);
        }

        $buildConfig->save();

        if ($log) {
            $log(':dn-synchronize-dependencies');
        }

        $this->synchronizeDependencies();
        $this->updateScriptManager();

        if ($log) {
            $log(':dn-compile-actions');
        }

        $this->actionManager->compile($this->project->getFile('src/'), function ($filename) use ($log) {
            $name = $this->project->getAbsoluteFile($filename)->getRelativePath();
            if ($log) {
                $log(':compile "' . $name . '"');
            }
        });

        $modules = $this->scriptComponentManager->getModules();
        $values = [];

        foreach ($modules as $module) {
            $values[] = 'app\\modules\\'
                . Str::replace(FileUtils::relativePath($this->project->getFile(self::SCRIPTS_DIRECTORY), $module), '/', '\\');
        }

        Json::toFile($this->project->getFile('src/.system/modules.json'), [
            'modules' => $values
        ]);

        if ($environment == Project::ENV_DEV) {
            if ($log) {
                $log(':dn-synchronize-debug-files');
            }

            $this->synchronizeDebugFiles();
        }
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

    public function doOpen()
    {
        $tree = $this->project->getTree();

        $tree->register(new FormProjectTreeNavigation(self::FORMS_DIRECTORY));
        //$tree->register(new FactoryProjectTreeNavigation(self::FACTORY_DIRECTORY));
        $tree->register(new ModuleProjectTreeNavigation(self::SCRIPTS_DIRECTORY));
        $tree->register(new SpriteProjectTreeNavigation(self::GAME_DIRECTORY . '/sprites'));

        $tree->getRoot()->root->children->add( $tree->createItemForFile($this->project->getFile('src/.theme/style.css'))->getOrigin() );

        /*$formsItem = $tree->getOrCreateItem('forms', 'Формы', 'icons/folder16.png', $this->project->getFile(self::FORMS_DIRECTORY));
        $formsItem->setExpanded(true);
        $formsItem->setDisableDelete(true);

        $formsItem->onUpdate(function () {
            $this->updateFormsInTree();
        });

        WatcherSystem::addPathRecursive($this->project->getFile(self::FORMS_DIRECTORY));
        $tree->addIgnoreRule('^src\\/\\.forms\\/.*\\.conf$');
        $tree->addIgnoreRule('^src\\/\\.scripts\\/.*\\.json');

        $projectTreeItem = $tree->getOrCreateItem(
            'scripts', 'Модули', 'icons/folder16.png', $this->project->getFile(self::SCRIPTS_DIRECTORY)
        );
        $projectTreeItem->setExpanded(true);
        $projectTreeItem->setDisableDelete(true);

        $projectTreeItem->onUpdate(function () {
            $this->updateScriptsInTree();
        });
        WatcherSystem::addPathRecursive($this->project->getFile(self::SCRIPTS_DIRECTORY));  */

        /** @var GradleProjectBehaviour $gradleBehavior */
        $gradleBehavior = $this->project->getBehaviour(GradleProjectBehaviour::class);

        $buildConfig = $gradleBehavior->getConfig();

        $buildConfig->addPlugin('application');
        $buildConfig->setDefine('mainClassName', '"php.runtime.launcher.Launcher"');
        $buildConfig->setSourceSet('main.resources.srcDirs', 'src');

        $buildConfig->setDefine('jar.archiveName', '"dn-compiled-module.jar"');

        $this->updateScriptManager();
        $this->updateSpriteManager();

        try {
            $this->applicationConfig->load($this->project->getFile('src/.system/application.conf'));
        } catch (IOException $e) {
            Logger::warn("Unable to load application.conf, {$e->getMessage()}");
        }

        $this->mainForm = $this->applicationConfig->get('app.mainForm', 'MainForm');
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

        $this->_recoverDirectories();

        $this->project->defineFile('src/JPHP-INF/.bootstrap.php', new GuiBootstrapFileTemplate());
        $this->project->defineFile('src/.system/application.conf', new GuiApplicationConfFileTemplate($this->project));
    }

    /*public function updateFormsInTree()
    {
        $tree = $this->project->getTree();

        $tree->updateDirectory('forms', $this->project->getFile(self::FORMS_DIRECTORY));
    } */

    public function updateScriptManager()
    {
        $this->scriptComponentManager->removeAll();
        $this->scriptComponentManager->updateByPath($this->project->getFile(self::SCRIPTS_DIRECTORY));
    }

    public function updateSpriteManager()
    {
        $this->spriteManager->reloadAll();
    }

    /*public function updateScriptsInTree()
    {
        $tree = $this->project->getTree();

        $tree->updateDirectory('scripts', $this->project->getFile(self::SCRIPTS_DIRECTORY));
    } */

    public function recoveryFactory($filename)
    {
        $file = $this->project->getAbsoluteFile($filename);

        $sources = $file->findLinkByExtension('php');

        $rel = FileUtils::relativePath($this->project->getFile(self::FACTORY_DIRECTORY), $filename);
        $rel = Str::sub($rel, 0, Str::length($rel) - 8);
        $rel = Str::replace($rel, '\\', '/');

        if (!$sources) {
            $sources = $this->project->getFile("src/app/factories/$rel.php");
            $file->addLink($sources);
        }

        if (!$sources->exists() && !Files::exists("$sources.source")) {
            $this->createFactory($rel);
        }
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

        if (!$sources->exists() && !Files::exists("$sources.source")) {
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

        if (!$sources->exists() && !Files::exists("$sources.source")) {
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
        $template->setNamespace('app\\modules');
        $template->setImports([
            'php\\gui\\framework\\AbstractModule'
        ]);

        $sources = $file->findLinkByExtension('php');

        if (!$sources) {
            $sources = $this->project->createFile("src/app/modules/$name.php", $template);
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
     * @return FormEditor[]
     */
    public function getFormEditors()
    {
        $formDir = $this->project->getFile(self::FORMS_DIRECTORY);

        $editors = [];

        FileUtils::scan($formDir, function ($filename) use (&$editors) {
            if (FileUtils::getExtension($filename) == "fxml") {
                $editor = FileSystem::fetchEditor($filename, true);

                $editors[FileUtils::hashName($filename)] = $editor;
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

    public function createPrototype($name)
    {
        Logger::info("Creating prototype '$name' ...");

        $template = new PhpClassFileTemplate($name, 'GameObject');
        $template->setNamespace("app\\prototypes");
        $template->setImports([
            'game\\GameObject'
        ]);

        $prototype = $this->project->createFile(self::PROTOTYPES_DIRECTORY . "/$name.php", $template);

        Logger::info("Finish creating prototype '$name'");
        return $prototype;
    }

    public function createFactory($name)
    {
        Logger::info("Creating factory '$name' ...'");

        $factory = $this->project->createFile(self::FACTORY_DIRECTORY . "/$name.factory", new GuiFormFileTemplate());

        $template = new PhpClassFileTemplate($name, 'AbstractFactory');
        $template->setNamespace('app\\factories');
        $template->setImports([
            'php\\gui\\framework\\AbstractFactory'
        ]);

        $sources = $factory->findLinkByExtension('php');

        if (!$sources) {
            $sources = $this->project->createFile("src/app/factories/$name.php", $template);
            $factory->addLink($sources);
        } else {
            if (!$sources->exists()) {
                $sources->applyTemplate($template);
                $sources->updateTemplate(true);
            }
        }

        Logger::info("Finish creating factory '$name'");

        return $factory;
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
        return $this->hasForm($name) ? FileSystem::fetchEditor($this->project->getFile("src/.forms/$name.fxml")) : null;
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
        $template->setNamespace('app\\forms');
        $template->setImports([
            'php\\gui\\framework\\AbstractForm'
        ]);

        $sources = $form->findLinkByExtension('php');

        if (!$sources) {
            $sources = $this->project->createFile("src/app/forms/$name.php", $template);
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

            foreach ($editor->getObjectList() as $it) {
                if ($it->element && $it->element->canBePrototype()) {
                    $it->group = $editor->getTitle();
                    $it->value = "{$it->getGroup()}.{$it->value}";
                    $elements[] = $it;
                }
            }
        }

        return $elements;
    }

    public function getObjectListOfForm($formName)
    {
        $result = [];
        $project = $this->project;

        $index = $project->getIndexer()->get($this->project->getFile("src/.forms/$formName.fxml"), '_objects');

        foreach ((array) $index as $it) {
            /** @var AbstractFormElement $element */
            $element = class_exists($it['type']) ? new $it['type']() : null;

            $result[] = $item = new ObjectListEditorItem(
                $it['id'], null
            );

            $item->hint = $element ? $element->getName() : '';
            $item->element = $element;
            $item->version = (int) $it['version'];

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
                $name = str::replace($module, "app\\modules\\", "");
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

        $this->project->getFile(self::FACTORY_DIRECTORY)->scan(function ($name, File $file) {
            if ($file->isFile() && Str::endsWith($name, '.factory')) {
                $this->recoveryFactory($file);
            }
        });

        $this->project->getFile('src/.scripts')->scan(function ($name, File $file) {
            if ($file->isDirectory()) {
                $this->recoveryModule($file);
            }
        });
    }

    public function synchronizeDependencies()
    {
        Logger::info("synchronizeDependencies ...");

        /** @var GradleProjectBehaviour $gradleBehavior */
        $gradleBehavior = $this->project->getBehaviour(GradleProjectBehaviour::class);

        $buildConfig = $gradleBehavior->getConfig();

        $libPath = $this->project->getFile('lib/');
        $libPath->mkdirs();

        foreach ($buildConfig->getDependencies() as $dep) {
            if (!$dep[0] && !$dep[2]) {
                $libFile = $this->findLibFile($dep[1]);

                if ($libFile) {
                    FileUtils::copyFile($libFile, "$libPath/$dep[1].jar");
                }
            }
        }
    }

    public function synchronizeDebugFiles()
    {
        Logger::info("synchronizeDebugFiles ...");

        $debugDir = Ide::get()->getOwnFile('debug/gui');

        if (!$debugDir->isDirectory() && Ide::get()->isDevelopment()) {
            $debugDir = Ide::get()->getOwnFile('misc/debug/gui');
        }

        if ($debugDir->isDirectory()) {
            FileUtils::scan($debugDir, function ($file) use ($debugDir) {
                $name = FileUtils::relativePath($debugDir, $file);

                FileUtils::copyFile($file, $this->project->getFile('src/.debug/' . $name));
            });
        }
    }

    /**
     * @return ScriptComponentManager
     */
    public function getScriptComponentManager()
    {
        return $this->scriptComponentManager;
    }

    private function findLibFile($name)
    {
        /** @var File[] $libPaths */
        $libPaths = [Ide::get()->getOwnFile('lib/')];

        if (Ide::get()->isDevelopment()) {
            $ownFile = Ide::get()->getOwnFile('build/install/develnext/lib');
            $libPaths[] = $ownFile;
        }

        $regex = Regex::of('(\.[0-9]|\-[0-9])');

        $name = $regex->with($name)->replace('');

        foreach ($libPaths as $libPath) {
            foreach ($libPath->findFiles() as $file) {
                $filename = $regex->with($file->getName())->replace('');

                if (Str::endsWith($filename, '.jar') || Str::endsWith($filename, '-SNAPSHOT.jar')) {
                    $filename = Str::sub($filename, 0, Str::length($filename) - 4);

                    if (Str::endsWith($filename, '-SNAPSHOT')) {
                        $filename = Str::sub($filename, 0, Str::length($filename) - 9);
                    }

                    if ($filename == $name) {
                        return $file;
                    }
                }
            }
        }

        return null;
    }
}