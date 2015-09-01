<?php
namespace ide;

use ide\account\AccountManager;
use ide\commands\CloseProjectCommand;
use ide\commands\ExitCommand;
use ide\commands\NewProjectCommand;
use ide\commands\OpenProjectCommand;
use ide\commands\SaveProjectCommand;
use ide\editors\AbstractEditor;
use ide\editors\value\BooleanPropertyEditor;
use ide\editors\value\ColorPropertyEditor;
use ide\editors\value\ElementPropertyEditor;
use ide\editors\value\EnumPropertyEditor;
use ide\editors\value\FloatPropertyEditor;
use ide\editors\value\FontPropertyEditor;
use ide\editors\value\IdPropertyEditor;
use ide\editors\value\ImagePropertyEditor;
use ide\editors\value\IntegerPropertyEditor;
use ide\editors\value\ModuleListPropertyEditor;
use ide\editors\value\PercentPropertyEditor;
use ide\editors\value\PositionPropertyEditor;
use ide\editors\value\SimpleTextPropertyEditor;
use ide\editors\value\StringListPropertyEditor;
use ide\editors\value\TextPropertyEditor;
use ide\formats\AbstractFormat;
use ide\formats\form\ButtonFormElement;
use ide\formats\form\LabelFormElement;
use ide\formats\form\TextFieldFormElement;
use ide\formats\FormFormat;
use ide\formats\GuiFormFormat;
use ide\formats\PhpCodeFormat;
use ide\formats\ScriptFormat;
use ide\formats\ScriptModuleFormat;
use ide\formats\WelcomeFormat;
use ide\forms\MainForm;
use ide\forms\SplashForm;
use ide\misc\AbstractCommand;
use ide\project\AbstractProjectTemplate;
use ide\project\Project;
use ide\project\templates\DefaultGuiProjectTemplate;
use ide\systems\FileSystem;
use ide\systems\ProjectSystem;
use ide\systems\WatcherSystem;
use ide\utils\FileUtils;
use php\gui\framework\Application;
use php\gui\JSException;
use php\gui\layout\UXAnchorPane;
use php\gui\UXAlert;
use php\gui\UXApplication;
use php\gui\UXImage;
use php\gui\UXImageView;
use php\gui\UXMenu;
use php\gui\UXMenuItem;
use php\gui\UXTextArea;
use php\io\File;
use php\io\IOException;
use php\io\ResourceStream;
use php\io\Stream;
use php\lang\System;
use php\lib\Items;
use php\lib\Str;
use php\util\Configuration;
use php\util\Scanner;


/**
 * Class Ide
 * @package ide
 */
class Ide extends Application
{
    const JPHP_VERSION = '0.7.2';

    /** @var string */
    private $OS;

    /**
     * @var SplashForm
     */
    protected $splash;

    /**
     * @var AbstractFormat[]
     */
    protected $formats = [];

    /**
     * @var AbstractProjectTemplate[]
     */
    protected $projectTemplates = [];

    /**
     * @var AbstractCommand[]
     */
    protected $commands = [];

    /**
     * @var callable
     */
    protected $afterShow = [];

    /**
     * @var Configuration[]
     */
    protected $configurations = [];

    /**
     * @var Project
     */
    protected $openedProject = null;

    /**
     * @var AccountManager
     */
    protected $accountManager = null;

    /**
     * @var string
     */
    protected $mode = 'prod';

    public function __construct($configPath = null)
    {
        parent::__construct($configPath);

        $this->OS = Str::lower(System::getProperty('os.name'));

        $env = System::getEnv();

        if (isset($env['DEVELNEXT_MODE'])) {
            $this->mode = $env['DEVELNEXT_MODE'];
        }
    }

    public function launch()
    {
        parent::launch(
            function () {
                restore_exception_handler();

                set_exception_handler(function (\BaseException $e) {
                    static $showError;

                    if ($e instanceof JSException) {
                        echo $e->getTraceAsString();
                        return;
                    }

                    if (!$showError) {
                        $showError = true;

                        $dialog = new UXAlert('ERROR');
                        $dialog->title = 'Ошибка';
                        $dialog->headerText = 'Произошла ошибка в DevelNext, сообщите об этом авторам';
                        $dialog->contentText = $e->getMessage();
                        $dialog->setButtonTypes(['Выход из DevelNext', 'Продолжить']);

                        $pane = new UXAnchorPane();
                        $pane->maxWidth = 100000;

                        $content = new UXTextArea("Ошибка в файле '{$e->getFile()}'\n\t-> на строке {$e->getLine()}\n\n" . $e->getTraceAsString());
                        $content->padding = 10;
                        UXAnchorPane::setAnchor($content, 0);

                        $pane->add($content);
                        $dialog->expandableContent = $pane;
                        $dialog->expanded = true;

                        switch ($dialog->showAndWait()) {
                            case 'Выход из DevelNext':
                                Ide::get()->shutdown();
                                break;
                        }

                        $showError = false;
                    }
                });

                if ($this->isDevelopment()) {
                    restore_exception_handler();
                }

                $this->registerAll();

                $this->splash = $splash = new SplashForm();
                $splash->show();

            },
            function () {
                foreach ($this->afterShow as $handle) {
                    $handle();
                }

                $this->accountManager = new AccountManager();
            }
        );
    }

    public function isWindows()
    {
        return Str::contains($this->OS, 'win');
    }

    public function isLinux()
    {
        return Str::contains($this->OS, 'nix') || Str::contains($this->OS, 'nux') || Str::contains($this->OS, 'aix');
    }

    public function isMac()
    {
        return Str::contains($this->OS, 'mac');
    }

    public function makeEnvironment()
    {
        $env = System::getEnv();

        $env['JAVA_HOME'] = $this->getJrePath();
        $env['GRADLE_HOME'] = $this->getGradlePath();

        return $env;
    }

    public function getInnoSetupProgram()
    {
        $innoPath = $this->getOwnFile('tools/innoSetup/ISCC.exe');

        if ($this->isDevelopment() && !$innoPath->exists()) {
            $innoPath = $this->getOwnFile('../develnext-tools/innoSetup/ISCC.exe');
        }

        return $innoPath && $innoPath->exists() ? $innoPath->getCanonicalFile() : null;
    }

    public function getLaunch4JProgram()
    {
        $launch4jPath = $this->getOwnFile('tools/Launch4j/launch4jc.exe');

        if ($this->isDevelopment() && !$launch4jPath->exists()) {
            $launch4jPath = $this->getOwnFile('../develnext-tools/Launch4j/launch4jc.exe');
        }

        return $launch4jPath && $launch4jPath->exists() ? $launch4jPath->getCanonicalFile() : null;
    }

    public function getGradleProgram()
    {
        $gradlePath = $this->getGradlePath();

        if ($gradlePath) {
            return FileUtils::normalizeName("$gradlePath/bin/gradle" . ($this->isWindows() ? '.bat' : ''));
        } else {
            return 'gradle';
        }

        //throw new \Exception("Unable to find gradle bin");
    }

    public function getGradlePath()
    {
        $gradlePath = $this->getOwnFile('tools/gradle');

        if ($this->isDevelopment() && !$gradlePath->exists()) {
            $gradlePath = $this->getOwnFile('../develnext-tools/gradle');
        }

        if (!$gradlePath->exists()) {
            $gradlePath = System::getEnv()['GRADLE_HOME'];

            if ($gradlePath) {
                $gradlePath = File::of($gradlePath);
            }
        }

        return $gradlePath && $gradlePath->exists() ? $gradlePath->getCanonicalFile() : null;
    }

    public function getJrePath()
    {
        if ($this->isWindows()) {
            $jrePath = $this->getOwnFile('tools/jre');

            if ($this->isDevelopment() && !$jrePath->exists()) {
                $jrePath = $this->getOwnFile('../develnext-tools/jre');
            }
        } else {
            $jrePath = null;
        }

        if (!$jrePath || !$jrePath->exists()) {
            $jrePath = System::getEnv()['JAVA_HOME'];

            if ($jrePath) {
                $jrePath = File::of($jrePath);
            }
        }

        return $jrePath && $jrePath->exists() ? $jrePath->getCanonicalFile() : null;
    }

    /**
     * @return bool
     */
    public function isDevelopment()
    {
        return Str::equalsIgnoreCase($this->mode, 'dev');
    }

    /**
     * @return bool
     */
    public function isProduction()
    {
        return Str::equalsIgnoreCase($this->mode, 'prod');
    }

    /**
     * @return SplashForm
     */
    public function getSplash()
    {
        return $this->splash;
    }

    public function setTitle($value)
    {
        $title = $this->getName() . ' ' . $this->getVersion();

        if ($value) {
            $title = $value . ' - ' . $title;
        }

        $this->getMainForm()->title = $title;
    }

    /**
     * @param string $name
     * @return Configuration
     */
    public function getUserConfig($name)
    {
        if ($config = $this->configurations[$name]) {
            return $config;
        }

        $config = new Configuration();

        try {
            $config->load($this->getFile("$name.conf"));
        } catch (IOException $e) {
            // ...
        }

        return $this->configurations[$name] = $config;
    }

    /**
     * @param string $key
     * @param mixed $def
     *
     * @return string
     */
    public function getUserConfigValue($key, $def = null)
    {
        return $this->getUserConfig('ide')->get($key, $def);
    }

    /**
     * @param $key
     * @param $value
     */
    public function setUserConfigValue($key, $value)
    {
        $this->getUserConfig('ide')->set($key, $value);
    }

    /**
     * @param string $path
     *
     * @return File
     */
    public function getOwnFile($path)
    {
        $home = "./";

        return File::of("$home/$path");
    }

    /**
     * @param string $path
     *
     * @return File
     */
    public function getFile($path)
    {
        $home = System::getProperty('user.home');

        $ideHome = File::of("$home/.DevelNext");

        if (!$ideHome->isDirectory()) {
            $ideHome->mkdirs();
        }

        return File::of("$ideHome/$path");
    }

    /**
     * @return project\AbstractProjectTemplate[]
     */
    public function getProjectTemplates()
    {
        return $this->projectTemplates;
    }

    /**
     * @param AbstractProjectTemplate $template
     */
    public function registerProjectTemplate(AbstractProjectTemplate $template)
    {
        $class = get_class($template);

        if (isset($this->projectTemplates[$class])) {
            return;
        }

        $this->projectTemplates[$class] = $template;
    }

    /**
     * @param AbstractFormat $format
     */
    public function registerFormat(AbstractFormat $format)
    {
        $class = get_class($format);

        if (isset($this->formats[$class])) {
            return;
        }

        $this->formats[$class] = $format;

        foreach ($format->getRequireFormats() as $el) {
            $this->registerFormat($el);
        }
    }

    public function unregisterCommands()
    {
        /** @var MainForm $mainForm */
        $mainForm = $this->getMainForm();

        if (!$mainForm) {
            return;
        }

        foreach ($this->commands as $code => $data) {
            /** @var AbstractCommand $command */
            $command = $data['command'];

            if ($command->isAlways()) {
                continue;
            }

            if ($data['headUi']) {
                if (is_array($data['headUi'])) {
                    foreach ($data['headUi'] as $ui) {
                        $mainForm->getHeadPane()->remove($ui);
                    }
                } else {
                    $mainForm->getHeadPane()->remove($data['headUi']);
                }
            }

            if ($data['menuItem']) {
                /** @var UXMenu $menu */
                $menu = $mainForm->{'menu' . Str::upperFirst($command->getCategory())};

                if ($menu instanceof UXMenu) {
                    $menu->items->remove($data['menuItem']);
                }
            }

            unset($this->commands[$code]);
        }
    }

    /**
     * @param $commandClass
     */
    public function executeCommand($commandClass)
    {
        $command = $this->commands[$commandClass];

        if ($command) {
            /** @var AbstractCommand $command */
            $command = $command['command'];
            $command->onExecute();
        } else {
            throw new \InvalidArgumentException("Unable to execute $commandClass command, it is not registered");
        }
    }

    /**
     * @param AbstractCommand $command
     */
    public function registerCommand(AbstractCommand $command)
    {
        $data = [
            'command' => $command,
        ];

        $headUi = $command->makeUiForHead();

        if ($headUi) {
            $data['headUi'] = $headUi;

            $this->afterShow(function () use ($headUi) {
                /** @var MainForm $mainForm */
                $mainForm = $this->getMainForm();

                if (!is_array($headUi)) {
                    $headUi = [$headUi];
                }

                foreach ($headUi as $ui) $mainForm->getHeadPane()->add($ui);
            });
        }

        $menuItem = $command->makeMenuItem();

        if ($menuItem) {
            $data['menuItem'] = $menuItem;

            $this->afterShow(function () use ($menuItem, $command) {
                /** @var MainForm $mainForm */
                $mainForm = $this->getMainForm();

                /** @var UXMenu $menu */
                $menu = $mainForm->{'menu' . Str::upperFirst($command->getCategory())};

                if ($menu instanceof UXMenu) {
                    if ($command->withBeforeSeparator()) {
                        /** @var UXMenuItem $last */
                        $last = $menu->items->last();

                        if ($last && $last->isSeparator()) {
                            // do nothing...
                        } else {
                            $menu->items->add(UXMenuItem::createSeparator());
                        }
                    }

                    $menu->items->add($menuItem);

                    if ($command->withAfterSeparator()) {
                        $menu->items->add(UXMenuItem::createSeparator());
                    }
                }
            });
        }

        $this->commands[get_class($command)] = $data;
    }

    /**
     * @param $class
     *
     * @return AbstractFormat
     */
    public function getRegisteredFormat($class)
    {
        return $this->formats[$class];
    }

    /**
     * @param string $path
     *
     * @return UXImageView
     */
    public function getImage($path)
    {
        if ($path === null) {
            return null;
        }

        if ($path instanceof UXImage) {
            $image = $path;
        } elseif ($path instanceof UXImageView) {
            return $path;
        } elseif ($path instanceof Stream) {
            $image = new UXImage($path);
        } else {
            $image = new UXImage('res://.data/img/' . $path);
        }

        $result = new UXImageView();
        $result->image = $image;

        return $result;
    }

    /**
     * @param $path
     *
     * @return AbstractFormat|null
     */
    public function getFormat($path)
    {
        /** @var AbstractFormat $format */
        foreach (Items::reverse($this->formats) as $format) {
            if ($format->isValid($path)) {
                return $format;
            }
        }

        return null;
    }

    /**
     * @return Project
     */
    public function getOpenedProject()
    {
        return $this->openedProject;
    }

    /**
     * @param Project $openedProject
     */
    public function setOpenedProject($openedProject = null)
    {
        $this->openedProject = $openedProject;

        if ($openedProject) {
            $this->setTitle($openedProject->getName() . " - [" . $openedProject->getRootDir() . "]");
        } else {
            $this->setTitle(null);
        }
    }

    /**
     * @param $path
     *
     * @return AbstractEditor
     */
    public function createEditor($path)
    {
        $format = $this->getFormat($path);

        if ($format) {
            $editor = $format->createEditor($path);

            if ($editor) {
                $editor->setFormat($format);
            }

            return $editor;
        }

        return null;
    }

    /**
     * @return AccountManager
     */
    public function getAccountManager()
    {
        return $this->accountManager;
    }

    public function registerAll()
    {
        ElementPropertyEditor::register(new SimpleTextPropertyEditor());
        ElementPropertyEditor::register(new TextPropertyEditor());
        ElementPropertyEditor::register(new IntegerPropertyEditor());
        ElementPropertyEditor::register(new FloatPropertyEditor());
        ElementPropertyEditor::register(new ColorPropertyEditor());
        ElementPropertyEditor::register(new FontPropertyEditor());
        ElementPropertyEditor::register(new EnumPropertyEditor([]));
        ElementPropertyEditor::register(new PositionPropertyEditor());
        ElementPropertyEditor::register(new BooleanPropertyEditor());
        ElementPropertyEditor::register(new StringListPropertyEditor());
        ElementPropertyEditor::register(new ModuleListPropertyEditor());
        ElementPropertyEditor::register(new IdPropertyEditor());
        ElementPropertyEditor::register(new ImagePropertyEditor());
        ElementPropertyEditor::register(new PercentPropertyEditor());

        $this->registerFormat(new WelcomeFormat());
        $this->registerFormat(new PhpCodeFormat());
        $this->registerFormat(new GuiFormFormat());
        $this->registerFormat(new ScriptFormat());
        $this->registerFormat(new ScriptModuleFormat());

        $this->registerProjectTemplate(new DefaultGuiProjectTemplate());

        $this->registerCommand(new NewProjectCommand());
        $this->registerCommand(new OpenProjectCommand());
        $this->registerCommand(new SaveProjectCommand());
        $this->registerCommand(new CloseProjectCommand());
        $this->registerCommand(new ExitCommand());

        $ideConfig = $this->getUserConfig('ide');

        if (!$ideConfig->has('projectDirectory')) {
            $ideConfig->set('projectDirectory', File::of(System::getProperty('user.home') . '/DevelNextProjects/'));
        }

        $this->afterShow(function () {
            $projectFile = $this->getUserConfigValue('lastProject');

            FileSystem::open('~welcome');

            if ($projectFile && File::of($projectFile)->exists()) {
                ProjectSystem::open($projectFile);
            }
        });
    }

    protected function afterShow(callable $handle)
    {
        if ($this->isLaunched()) {
            $handle();
        } else {
            $this->afterShow[] = $handle;
        }
    }

    /**
     * @return Ide
     * @throws \Exception
     */
    public static function get()
    {
        return parent::get();
    }

    public function shutdown()
    {
        $project = $this->getOpenedProject();

        $this->mainForm->hide();

        if ($project) {
            $project->save();
        }

        WatcherSystem::shutdown();

        $stream = null;

        foreach ($this->configurations as $name => $config) {
            try {
                $stream = Stream::of($this->getFile("$name.conf"), 'w+');
                $config->save($stream);
            } catch (IOException $e) {
                throw $e;
            } finally {
                if ($stream) $stream->close();
            }
        }

        try {
            parent::shutdown();
        } catch (\Exception $e) {
            System::halt(0);
        }
    }

    /**
     * @param $resourceName
     * @return array
     */
    public function getInternalList($resourceName)
    {
        static $cache;

        if ($result = $cache[$resourceName]) {
            return $result;
        }

        $resources = ResourceStream::getResources($resourceName);

        $result = [];

        foreach ($resources as $resource) {
            $scanner = new Scanner($resource, 'UTF-8');

            while ($scanner->hasNextLine()) {
                $line = Str::trim($scanner->nextLine());

                if ($line) {
                    $result[] = $line;
                }
            }
        }

        return $cache[$resourceName] = $result;
    }

    /**
     * @param string $include
     *
     * @return File
     */
    public function getIncludeFile($include)
    {
        if ($this->isDevelopment()) {
            return $this->getOwnFile("misc/include/" . $include);
        } else {
            return $this->getOwnFile("include/" . $include);
        }
    }
}