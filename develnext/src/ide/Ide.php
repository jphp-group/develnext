<?php
namespace ide;

use Files;
use ide\account\AccountManager;
use ide\account\ServiceManager;
use ide\editors\AbstractEditor;
use ide\editors\value\ElementPropertyEditor;
use ide\formats\AbstractFormat;
use ide\forms\MainForm;
use ide\forms\SplashForm;
use ide\library\IdeLibrary;
use ide\misc\AbstractCommand;
use ide\misc\EventHandlerBehaviour;
use ide\project\AbstractProjectTemplate;
use ide\project\Project;
use ide\protocol\AbstractProtocolHandler;
use ide\protocol\handlers\FileOpenProjectProtocolHandler;
use ide\systems\Cache;
use ide\systems\FileSystem;
use ide\systems\IdeSystem;
use ide\systems\ProjectSystem;
use ide\systems\WatcherSystem;
use ide\ui\LazyLoadingImage;
use ide\ui\Notifications;
use ide\utils\FileUtils;
use php\gui\framework\Application;
use php\gui\JSException;
use php\gui\layout\UXAnchorPane;
use php\gui\UXAlert;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\gui\UXImage;
use php\gui\UXImageView;
use php\gui\UXMenu;
use php\gui\UXMenuItem;
use php\gui\UXSeparator;
use php\gui\UXTextArea;
use php\io\File;
use php\io\IOException;
use php\io\ResourceStream;
use php\io\Stream;
use php\lang\System;
use php\lang\Thread;
use php\lib\fs;
use php\lib\Items;
use php\lib\Str;
use php\util\Configuration;
use php\util\Scanner;
use script\TimerScript;
use timer\AccurateTimer;


/**
 * Class Ide
 * @package ide
 */
class Ide extends Application
{
    const JPHP_VERSION = '0.7.2';

    use EventHandlerBehaviour;

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
     * @var AbstractExtension[]
     */
    protected $extensions = [];

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
     * @var ServiceManager
     */
    protected $serviceManager = null;

    /**
     * @var IdeLibrary
     */
    protected $library;

    /**
     * @var boolean
     */
    protected $idle = false;

    /**
     * @var string
     */
    protected $mode = 'prod';

    public function __construct($configPath = null)
    {
        parent::__construct($configPath);

        $this->OS = IdeSystem::getOs();
        $this->mode = IdeSystem::getMode();

        $this->library = new IdeLibrary($this);
    }

    public function launch()
    {
        parent::launch(
            function () {
                Logger::reset();
                Logger::info("Start IDE, mode = $this->mode, os = $this->OS, version = {$this->getVersion()}");
                Logger::info(str::format("Commands Args = [%s]", str::join((array)$GLOBALS['argv'], ', ')));

                restore_exception_handler();

                set_exception_handler(function (\BaseException $e) {
                    static $showError;

                    if ($e instanceof JSException) {
                        echo $e->getTraceAsString();
                        return;
                    }

                    Logger::exception($e->getMessage(), $e);

                    if (!$showError) {
                        $showError = true;
                        $notify = Notifications::error('Непредвиденная ошибка', 'Возникла неожиданная ошибка, пожалуйста нажмите сюда, чтобы узнать подробности.');

                        $notify->on('click', function () use ($e) {
                            $dialog = new UXAlert('ERROR');
                            $dialog->title = 'Ошибка';
                            $dialog->headerText = 'Произошла ошибка в DevelNext, сообщите об этом авторам';
                            $dialog->contentText = $e->getMessage();
                            $dialog->setButtonTypes(['Выход из DevelNext', 'Продолжить']);
                            $pane = new UXAnchorPane();
                            $pane->maxWidth = 100000;
                            $content = new UXTextArea("{$e->getMessage()}\n\nОшибка в файле '{$e->getFile()}'\n\t-> на строке {$e->getLine()}\n\n" . $e->getTraceAsString());
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
                        });

                        $notify->on('hide', function () use (&$showError) {
                            $showError = false;
                        });
                    }
                });

                if ($this->isDevelopment()) {
                    restore_exception_handler();
                }

                if ($this->handleArgs($GLOBALS['argv'])) {
                    Logger::info("Protocol handler is shutdown ide ...");

                    TimerScript::executeAfter(7000, function () {
                        $this->shutdown();
                    });
                    return;
                }

                $this->splash = $splash = new SplashForm();
                $splash->show();

                TimerScript::executeAfter(1000, function () {
                    $this->registerAll();

                    foreach ($this->extensions as $extension) {
                        $extension->onIdeStart();
                    }

                    $this->trigger('start', []);
                });
            },
            function () {
                foreach ($this->afterShow as $handle) {
                    $handle();
                }

                $this->serviceManager = new ServiceManager();

                $this->serviceManager->on('privateEnable', function () {
                    /*UXApplication::runLater(function () {
                        Notifications::showAccountWelcome();
                    });*/
                    $this->accountManager->updateAccount();
                });

                $this->serviceManager->on('privateDisable', function () {
                    Notifications::showAccountUnavailable();
                });

                $this->serviceManager->updateStatus();

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

    /**
     * @return IdeLibrary
     */
    public function getLibrary()
    {
        return $this->library;
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
        $innoPath = new File($this->getToolPath(), '/innoSetup/ISCC.exe');

        return $innoPath && $innoPath->exists() ? $innoPath->getCanonicalFile() : null;
    }

    public function getLaunch4JProgram()
    {
        $launch4jPath = new File($this->getToolPath(), '/Launch4j/launch4jc.exe');

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

    public function getToolPath()
    {
        $launcher = System::getProperty('develnext.launcher');

        switch ($launcher) {
            case 'root':
                $path = $this->getOwnFile('tools/');
                break;
            default:
                $path = $this->getOwnFile('../tools/');
        }

        if ($this->isDevelopment() && !$path->exists()) {
            $path = $this->getOwnFile('../develnext-tools/');
        }

        $file = $path && $path->exists() ? $path->getAbsoluteFile() : null;

        Logger::info("Detect tool path: '$file', mode = {$this->mode}");

        return $file;
    }

    public function getGradlePath()
    {
        $gradlePath = new File($this->getToolPath(), '/gradle');

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
        $path = $this->getToolPath();

        if ($this->isWindows() || $this->isLinux()) {
            $jrePath = new File($path, '/jre');
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
        return IdeSystem::isDevelopment();
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
        $name = FileUtils::normalizeName($name);

        if ($config = $this->configurations[$name]) {
            return $config;
        }

        $config = new Configuration();

        try {
            $file = $this->getFile("$name.conf");

            $config->load($file);
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
     * @param string $key
     * @param mixed $def
     *
     * @return string
     */
    public function getUserConfigArrayValue($key, $def = [])
    {
        if ($this->getUserConfig('ide')->has($key)) {
            return $this->getUserConfig('ide')->getArray($key, $def);
        } else {
            return $def;
        }
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
    public static function getOwnFile($path)
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
        return IdeSystem::getFile($path);
    }

    /**
     * @param string $suffix
     * @return File
     */
    public function createTempFile($suffix = '')
    {
        $tempDir = $this->getFile('tmp');

        if (!Files::isDir($tempDir)) {
            if (Files::exists($tempDir)) {
                Files::delete($tempDir);
            }
        }

        $tempDir->mkdirs();

        $file = File::createTemp(Str::random(5), Str::random(10) . $suffix, $tempDir);
        $file->deleteOnExit();
        return $file;
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

        $createCommand = $format->createBlankCommand();

        if ($createCommand) {
            $this->registerCommand($createCommand, 'create');
        }

        foreach ($format->getRequireFormats() as $el) {
            $this->registerFormat($el);
        }
    }

    public function unregisterCommand($commandClass, $ignoreAlways = true)
    {
        /** @var MainForm $mainForm */
        $mainForm = $this->getMainForm();

        $data = $this->commands[$commandClass];

        if (!$data) {
            return;
        }

        /** @var AbstractCommand $command */
        $command = $data['command'];

        if (!$ignoreAlways && $command->isAlways()) {
            return;
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

        if ($data['headRightUi']) {
            if (is_array($data['headRightUi'])) {
                foreach ($data['headRightUi'] as $ui) {
                    $mainForm->getHeadRightPane()->remove($ui);
                }
            } else {
                $mainForm->getHeadRightPane()->remove($data['headRightUi']);
            }
        }

        if ($data['menuItem']) {
            /** @var UXMenu $menu */
            $menu = $mainForm->{'menu' . Str::upperFirst($command->getCategory())};

            if ($menu instanceof UXMenu) {
                foreach ($data['menuItem'] as $el) {
                    $menu->items->remove($el);
                }
            }
        }

        unset($this->commands[$commandClass]);
    }

    public function unregisterCommands()
    {
        /** @var MainForm $mainForm */
        $mainForm = $this->getMainForm();

        if (!$mainForm) {
            return;
        }

        foreach ($this->commands as $code => $data) {
            $this->unregisterCommand($code, false);
        }
    }

    /**
     * @param $commandClass
     */
    public function executeCommand($commandClass)
    {
        Logger::info("Execute command - $commandClass");

        $command = $this->getRegisteredCommand($commandClass);

        if ($command) {
            $command->onExecute();
        } else {
            throw new \InvalidArgumentException("Unable to execute $commandClass command, it is not registered");
        }
    }

    /**
     * @param $commandClass
     * @return AbstractCommand|null
     */
    public function getRegisteredCommand($commandClass)
    {
        $command = $this->commands[$commandClass];

        if ($command) {
            /** @var AbstractCommand $command */
            $command = $command['command'];
            return $command;
        }

        return null;
    }

    /**
     * @param AbstractCommand $command
     * @param null $category
     */
    public function registerCommand(AbstractCommand $command, $category = null)
    {
        $this->unregisterCommand(get_class($command));

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

                foreach ($headUi as $ui) {
                    if ($ui instanceof UXButton) {
                        $ui->padding = 8;
                    } else if ($ui instanceof UXSeparator) {
                        $ui->paddingLeft = 4;
                        $ui->paddingRight = 2;
                    }

                    $mainForm->getHeadPane()->children->insert($mainForm->getHeadPane()->children->count - 1, $ui);
                }
            });
        }

        $headRightUi = $command->makeUiForRightHead();

        if ($headRightUi) {
            $data['headRightUi'] = $headRightUi;

            $this->afterShow(function () use ($headRightUi) {
                /** @var MainForm $mainForm */
                $mainForm = $this->getMainForm();

                if (!is_array($headRightUi)) {
                    $headRightUi = [$headRightUi];
                }

                foreach ($headRightUi as $ui) {
                    if ($ui instanceof UXButton) {
                        $ui->padding = 8;
                    } else if ($ui instanceof UXSeparator) {
                        $ui->paddingLeft = 4;
                        $ui->paddingRight = 2;
                    }

                    $mainForm->getHeadRightPane()->add($ui);
                }
            });
        }

        $category = $category ?: $command->getCategory();
        $menuItem = $command->makeMenuItem();

        if ($menuItem) {
            $data['menuItem'] = $menuItem;

            $this->afterShow(function () use ($menuItem, $command, &$data, $category) {
                /** @var MainForm $mainForm */
                $mainForm = $this->getMainForm();

                /** @var UXMenu $menu */
                $menu = $mainForm->{'menu' . Str::upperFirst($category)};

                if ($menu instanceof UXMenu) {
                    $items = [];

                    if ($command->withBeforeSeparator()) {
                        /** @var UXMenuItem $last */
                        $last = $menu->items->last();

                        if ($last && $last->isSeparator()) {
                            // do nothing...
                        } else {
                            $items[] = UXMenuItem::createSeparator();
                        }
                    }

                    $items[] = $menuItem;

                    if ($command->withAfterSeparator()) {
                        $items[] = UXMenuItem::createSeparator();
                    }

                    foreach ($items as $el) {
                        $menu->items->add($el);
                    }

                    $data['menuItem'] = $items;
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
     * @param array $size
     * @param bool $cache
     * @return UXImageView
     */
    public function getImage($path, array $size = null, $cache = true)
    {
        if ($path === null) {
            return null;
        }

        if ($path instanceof UXImage) {
            $image = $path;
        } elseif ($path instanceof UXImageView) {
            if ($size) {
                $image = $path->image;

                if ($image == null) {
                    return null;
                }
            } else {
                return $path;
            }
        } elseif ($path instanceof Stream) {
            $image = new UXImage($path);
        } elseif ($path instanceof LazyLoadingImage) {
            $image = $path->getImage();
        } else {
            if ($cache) {
                $image = Cache::getResourceImage("res://.data/img/" . $path);
            } else {
                $image = new UXImage('res://.data/img/' . $path);
            }
        }

        $result = new UXImageView();
        $result->image = $image;

        if ($size) {
            $result->size = $size;
            $result->preserveRatio = true;
        }

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
        $extensions = $this->getInternalList('.dn/extensions');

        foreach ($extensions as $extension) {
            Logger::info("Register IDE extension $extension");

            /** @var AbstractExtension $extension */
            $extension = new $extension();

            if (!($extension instanceof AbstractExtension)) {
                throw new IdeException("Unable to add extension " . get_class($extension) . ", is not correct type");
            }

            $this->extensions[get_class($extension)] = $extension;
            $extension->onRegister();
        }

        $valueEditors = $this->getInternalList('.dn/propertyValueEditors');
        foreach ($valueEditors as $valueEditor) {
            $valueEditor = new $valueEditor();

            ElementPropertyEditor::register($valueEditor);
        }

        $formats = $this->getInternalList('.dn/formats');
        foreach ($formats as $format) {
            $this->registerFormat(new $format());
        }

        $projectTemplates = $this->getInternalList('.dn/projectTemplates');
        foreach ($projectTemplates as $projectTemplate) {
            $this->registerProjectTemplate(new $projectTemplate());
        }

        $mainCommands = $this->getInternalList('.dn/mainCommands');
        foreach ($mainCommands as $commandClass) {
            /** @var AbstractCommand $command */
            $command = new $commandClass();

            $this->registerCommand($command);
        }

        /** @var AccurateTimer $inactiveTimer */
        $inactiveTimer = null;

        $this->getMainForm()->addEventFilter('mouseMove', function () use (&$inactiveTimer) {
            if ($inactiveTimer) {
                $inactiveTimer->stop();
                $inactiveTimer = null;
            }

            if ($this->idle) {
                Logger::info("IDE awake, idle mode = off ...");
                $this->trigger('idleOff');
            }

            $this->idle = false;

            // 5 min.
            $inactiveTimer = AccurateTimer::executeAfter(5 * 60 * 1000, function () {
                if (!$this->idle) {
                    $this->idle = true;
                    Logger::info("IDE is sleeping, idle mode ...");
                    $this->trigger('idleOn');
                }
            });
        });

        $ideConfig = $this->getUserConfig('ide');

        if (!$ideConfig->has('projectDirectory')) {
            $ideConfig->set('projectDirectory', File::of(System::getProperty('user.home') . '/DevelNextProjects/'));
        }

        $this->afterShow(function () {
            $projectFile = $this->getUserConfigValue('lastProject');

            FileSystem::open('~welcome');

            if ($projectFile && File::of($projectFile)->exists()) {
                ProjectSystem::open($projectFile, false);
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
     * @return boolean
     */
    public function isIdle()
    {
        return $this->idle;
    }

    /**
     * @return MainForm
     */
    public function getMainForm()
    {
        return parent::getMainForm();
    }


    /**
     * @return Ide
     * @throws \Exception
     */
    public static function get()
    {
        return parent::get();
    }

    /**
     * @return Project
     */
    public static function project()
    {
        return self::get()->getOpenedProject();
    }

    /**
     * @return AccountManager
     */
    public static function accountManager()
    {
        return Ide::get()->getAccountManager();
    }

    /**
     * @return ServiceManager
     */
    public static function service()
    {
        return Ide::get()->serviceManager;
    }

    /**
     * @param $text
     * @param int $timeout
     */
    public static function toast($text, $timeout = 0)
    {
        Ide::get()->getMainForm()->toast($text, $timeout);
    }

    public function shutdown()
    {
        (new Thread(function () {
            sleep(15);
            System::halt(0);
        }))->start();

        Logger::info("Start IDE shutdown ...");

        foreach ($this->extensions as $extension) {
            try {
                Logger::info("Shutdown IDE extension " . get_class($extension) . ' ...');
                $extension->onIdeShutdown();
            } catch (\Exception $e) {
                Logger::exception("Unable to shutdown IDE extension " . get_class($extension), $e);
            }
        }

        $project = $this->getOpenedProject();

        $this->mainForm->hide();

        if ($project) {
            $project->save();
            ProjectSystem::close();
        }

        WatcherSystem::shutdown();

        $stream = null;

        foreach ($this->configurations as $name => $config) {
            $name = FileUtils::normalizeName($name);

            try {
                Logger::info("Save IDE config ($name.conf)");
                $file = $this->getFile("$name.conf");
                $file->createNewFile(true);

                $stream = Stream::of($file, 'w+');
                $config->save($stream);
            } catch (IOException $e) {
                Logger::error("Unable to save config ($name), {$e->getMessage()}");
                //throw $e;
            } finally {
                if ($stream) $stream->close();
            }
        }

        if ($this->accountManager->isAuthorized()) {
            //Ide::service()->ide()->shutdownAsync(null);
        }

        Logger::info("Finish IDE shutdown");
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

                if ($line && !str::startsWith($line, '#')) {
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

    protected function handleArgs($argv)
    {
        $arg = $argv[1];

        if (str::startsWith($arg, 'develnext://')) {
            $arg = str::sub($arg, str::length('develnext://'));

            $protocolHandlers = $this->getInternalList('.dn/protocolHandlers');

            foreach ($protocolHandlers as $protocolHandler) {
                /** @var AbstractProtocolHandler $protocolHandler */
                $protocolHandler = new $protocolHandler();

                if ($protocolHandler->isValid($arg)) {
                    if ($protocolHandler->handle($arg)) {
                        return true;
                    }
                }
            }
        } else {
            if (fs::isFile($arg)) {
                $defProtocolHandler = new FileOpenProjectProtocolHandler();
                if ($defProtocolHandler->handle($arg)) {
                    return true;
                }
            }
        }

        return false;
    }
}