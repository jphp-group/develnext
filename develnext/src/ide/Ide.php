<?php
namespace ide;

use Files;
use ide\account\AccountManager;
use ide\account\ServiceManager;
use ide\bundle\AbstractBundle;
use ide\editors\AbstractEditor;
use ide\editors\value\ElementPropertyEditor;
use ide\formats\AbstractFormat;
use ide\formats\IdeFormatOwner;
use ide\forms\MainForm;
use ide\forms\SplashForm;
use ide\l10n\L10n;
use ide\library\IdeLibrary;
use ide\misc\AbstractCommand;
use ide\misc\EventHandlerBehaviour;
use ide\misc\GradleBuildConfig;
use ide\project\AbstractProjectTemplate;
use ide\project\control\AbstractProjectControlPane;
use ide\project\Project;
use ide\protocol\AbstractProtocolHandler;
use ide\protocol\handlers\FileOpenProjectProtocolHandler;
use ide\settings\AbstractSettings;
use ide\settings\AllSettings;
use ide\systems\Cache;
use ide\systems\FileSystem;
use ide\systems\IdeSystem;
use ide\systems\ProjectSystem;
use ide\systems\WatcherSystem;
use ide\tool\IdeToolManager;
use ide\ui\LazyLoadingImage;
use ide\ui\Notifications;
use ide\utils\FileUtils;
use ide\utils\Json;
use php\desktop\SystemTray;
use php\desktop\TrayIcon;
use php\gui\event\UXKeyboardManager;
use php\gui\event\UXKeyEvent;
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
use php\lang\IllegalArgumentException;
use php\lang\Process;
use php\lang\System;
use php\lang\Thread;
use php\lang\ThreadPool;
use php\lib\arr;
use php\lib\fs;
use php\lib\Items;
use php\lib\reflect;
use php\lib\Str;
use php\time\Time;
use php\time\Timer;
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
    use EventHandlerBehaviour;
    use IdeFormatOwner {
        getRegisteredFormat as _getRegisteredFormat;
    }

    /** @var string */
    private $OS;

    /**
     * @var SplashForm
     */
    protected $splash;

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
     * @var AbstractNavigation[]
     */
    protected $navigation = [];

    /**
     * @var callable
     */
    protected $afterShow = [];

    /**
     * @var IdeConfiguration[]
     */
    protected $configurations = [];

    /**
     * @var Project
     */
    protected $openedProject = null;

    /**
     * @var AbstractProjectControlPane[]
     */
    protected $projectControlPanes = [];

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
     * @var IdeToolManager
     */
    protected $toolManager;

    /**
     * @var boolean
     */
    protected $idle = false;

    /**
     * @var AbstractSettings[]
     */
    protected $settings = [];

    /**
     * @var L10n
     */
    protected $l10n;

    /**
     * @var IdeLanguage[]
     */
    protected $languages = [];

    /**
     * @var IdeLanguage
     */
    protected $language;

    /**
     * @var ThreadPool
     */
    private $asyncThreadPool;


    protected $disableOpenLastProject = false;

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
        $this->toolManager = new IdeToolManager();

        $this->asyncThreadPool = ThreadPool::createCached();
    }

    public function launch()
    {
        parent::launch(
            function () {
                Logger::reset();
                Logger::info("Start IDE, mode = $this->mode, os = $this->OS, version = {$this->getVersion()}");
                Logger::info(str::format("Commands Args = [%s]", str::join((array)$GLOBALS['argv'], ', ')));

                restore_exception_handler();

                set_exception_handler(function ($e) {
                    static $showError;

                    if ($e instanceof JSException) {
                        echo $e->getTraceAsString();
                        return;
                    }

                    Logger::exception($e->getMessage(), $e);

                    if (!$showError) {
                        $showError = true;
                        $notify = Notifications::error(_('error.unknown.title'), _('error.unknown.message'));

                        $notify->on('click', function () use ($e) {
                            $dialog = new UXAlert('ERROR');
                            $dialog->title = _('error.title');
                            $dialog->headerText = _('error.unknown.help.text');
                            $dialog->contentText = $e->getMessage();
                            $dialog->setButtonTypes([_('btn.exit.dn'), _('btn.resume')]);
                            $pane = new UXAnchorPane();
                            $pane->maxWidth = 100000;

                            $class = get_class($e);

                            $content = new UXTextArea("{$class}\n{$e->getMessage()}\n\n"
                                . _("error.in.file", $e->getFile())
                                . "\n\t-> " . _("error.at.line", $e->getLine()) . "\n\n" . $e->getTraceAsString());


                            $content->padding = 10;
                            UXAnchorPane::setAnchor($content, 0);
                            $pane->add($content);
                            $dialog->expandableContent = $pane;
                            $dialog->expanded = true;
                            switch ($dialog->showAndWait()) {
                                case _('btn.exit.dn'):
                                    Ide::get()->shutdown();
                                    break;
                            }
                        });

                        $this->sendError($e);

                        $notify->on('hide', function () use (&$showError) {
                            $showError = false;
                        });
                    }
                });

                if ($this->isDevelopment()) {
                    restore_exception_handler();
                }

                $this->readLanguages();

                if ($this->handleArgs($GLOBALS['argv'])) {
                    Logger::info("Protocol handler is shutdown ide ...");

                    Timer::after('7s', function () {
                        $this->shutdown();
                    });
                    return;
                }
            },
            function () {

                $this->setOpenedProject(null);

                foreach ($this->afterShow as $handle) {
                    $handle();
                }

                $this->serviceManager = new ServiceManager();

                $this->serviceManager->on('privateEnable', function () {
                    $this->accountManager->updateAccount();
                });

                $this->serviceManager->on('privateDisable', function () {
                    //Notifications::showAccountUnavailable();
                });

                $this->serviceManager->updateStatus();

                $this->accountManager = new AccountManager();

                $this->registerAll();

                foreach ($this->extensions as $extension) {
                    $extension->onIdeStart();
                }

                $timer = new AccurateTimer(1000, function () {
                    Ide::async(function () {
                        $file = FileSystem::getSelected();

                        foreach (FileSystem::getOpened() as $info) {
                            if (!fs::exists($info['file']) /*&& !FileUtils::equalNames($file, $info['file'])*/) {
                                uiLater(function () use ($info) {
                                    $editor = FileSystem::getOpenedEditor($info['file']);
                                    if ($editor->isAutoClose()) {
                                        $editor->delete();
                                    }
                                });
                            }
                        }
                    });
                });
                $timer->start();

                $this->trigger('start', []);
            }
        );
    }

    /**
     * Запустить коллбэка в очереди потоков IDE.
     *
     * @param callable $callback
     * @param callable|null $after
     */
    public static function async(callable $callback, callable $after = null)
    {
        $ide = self::get();

        if ($ide->asyncThreadPool->isShutdown() || $ide->asyncThreadPool->isTerminated()) {
            return;
        }

        $ide->asyncThreadPool->execute(function () use ($callback, $after) {
            $result = $callback();

            if ($after) {
                $after($result);
            }
        });
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

    /**
     * Менеджер тулов/утилит.
     *
     * @return IdeToolManager
     */
    public function getToolManager()
    {
        return $this->toolManager;
    }

    /**
     * Утилита для локализации.
     *
     * @return L10n
     */
    public function getL10n()
    {
        if (!$this->l10n && $this->language) {
            $language = $this->languages[$this->language->getAltLang()];
            $this->l10n = $this->language->getL10n($language ? $language->getL10n() : $language);
        }

        return $this->l10n;
    }

    /**
     * Текущий язык.
     *
     * @return IdeLanguage
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Списки доступных языков IDE.
     *
     * @return IdeLanguage[]
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param \Exception|\Error $e
     * @param string $context
     */
    public function sendError($e, $context = 'global')
    {
        if (Ide::service()->canPrivate() && Ide::accountManager()->isAuthorized()) {
            try {
                Ide::service()->ide()->sendErrorAsync($e, function () {

                });
            } catch (\Exception $e) {
                echo "Unable to send error, exception = {$e->getMessage()}\n";
            }
        }
    }

    public function makeEnvironment()
    {
        $env = System::getEnv();

        if ($this->getJrePath()) {
            $env['JAVA_HOME'] = $this->getJrePath();
        }

        if ($this->getGradlePath()) {
            $env['GRADLE_HOME'] = $this->getGradlePath();
        }

        if ($this->getApacheAntPath()) {
            $env['ANT_HOME'] = $this->getApacheAntPath();
        }

        return $env;
    }

    public function getInnoSetupProgram()
    {
        $innoPath = new File($this->getToolPath(), '/innoSetup/ISCC.exe');

        return $innoPath && $innoPath->exists() ? $innoPath->getCanonicalFile() : null;
    }

    public function getLaunch4JPath()
    {
        return fs::parent($this->getLaunch4JProgram());
    }

    public function getLaunch4JProgram()
    {
        if (Ide::get()->isWindows()) {
            $launch4jPath = new File($this->getToolPath(), '/Launch4j/launch4jc.exe');
        } else {
            $launch4jPath = new File($this->getToolPath(), '/Launch4jLinux/launch4j');
        }

        return $launch4jPath && $launch4jPath->exists() ? $launch4jPath->getCanonicalFile() : null;
    }

    public function getApacheAntProgram()
    {
        $antPath = $this->getApacheAntPath();

        if ($antPath) {
            return FileUtils::normalizeName("$antPath/bin/ant" . ($this->isWindows() ? '.bat' : ''));
        } else {
            return 'ant';
        }
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

    /**
     * Вернуть путь к папке tools IDE.
     *
     * @return null|string
     */
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

        $file = $path && $path->exists() ? fs::abs($path) : null;

        //Logger::info("Detect tool path: '$file', mode = {$this->mode}, launcher = {$launcher}");

        return $file;
    }

    /**
     * Вернуть путь к apache ant тулу IDE.
     *
     * @return null|File
     */
    public function getApacheAntPath()
    {
        $antPath = new File($this->getToolPath(), '/apache-ant');

        if (!$antPath->exists()) {
            $antPath = System::getEnv()['ANT_HOME'];

            if ($antPath) {
                $antPath = File::of($antPath);
            }
        }

        return $antPath && $antPath->exists() ? $antPath->getCanonicalFile() : null;
    }

    /**
     * Вернуть путь к Gradle дистрибутиву.
     *
     * @deprecated не используется больше!
     * @return null|File
     */
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

    /**
     * Вернуть путь к JRE среды (Java Runtime Environment).
     *
     * @return null|File
     */
    public function getJrePath()
    {
        $path = $this->getToolPath();

        if ($this->isWindows() || $this->isLinux()) {
            $jrePath = new File($path, '/jre');

            if ($this->isLinux() && (new File($path, '/jreLinux'))->isDirectory()) {
                $jrePath = new File($path, '/jreLinux');
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
     * Dev режим работы IDE?
     *
     * @return bool
     */
    public function isDevelopment()
    {
        return IdeSystem::isDevelopment();
    }

    /**
     * Prod режим работы IDE?
     *
     * @return bool
     */
    public function isProduction()
    {
        return Str::equalsIgnoreCase($this->mode, 'prod');
    }

    /**
     * Вернуть splash форму IDE.
     *
     * @return SplashForm
     */
    public function getSplash()
    {
        return $this->splash;
    }

    /**
     * Задать заголовок главной формы IDE.
     *
     * @param $value
     */
    public function setTitle($value)
    {
        $title = $this->getName() . ' ' . $this->getVersion();

        if ($value) {
            $title = $value . ' - ' . $title;
        }

        $this->getMainForm()->title = $title;
    }

    protected function readLanguages()
    {
        $this->languages = [];

        $directory = IdeSystem::getOwnFile('languages');

        if (self::isDevelopment() && !fs::isDir($directory)) {
            $directory = IdeSystem::getOwnFile('misc/languages');
        }

        fs::scan($directory, function ($path) {
            if (fs::isDir($path)) {
                $code = fs::name($path);

                Logger::info("Add ide language '$code', path = $path");

                $this->languages[$code] = new IdeLanguage($code, $path);
            }
        }, 1);

        $ideLanguage = $this->getUserConfigValue('ide.language', System::getProperty('user.language'));

        if (!$this->languages[$ideLanguage]) {
            $ideLanguage = 'en';
        }

        $this->language = $this->languages[$ideLanguage];

        if ($this->language) {
            $this->language->load();

            if ($altLanguage = $this->languages[$this->language->getAltLang()]) {
                $altLanguage->load();
            }
        }

        $this->setUserConfigValue('ide.language', $ideLanguage);
    }

    /**
     * Вернуть именнованный конфиг из системной папки IDE.
     *
     * @param string $name
     * @return IdeConfiguration
     */
    public function getUserConfig($name)
    {
        $name = FileUtils::normalizeName($name);

        if ($config = $this->configurations[$name]) {
            return $config;
        }

        try {
            $config = new IdeConfiguration(IdeSystem::getFile("$name.conf"));
        } catch (IOException $e) {
            // ...
        }

        return $this->configurations[$name] = $config;
    }

    /**
     * Вернуть значение глобального конфига, из ide.conf.
     *
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
     * Вернуть значение глобального конфига в виде массива, из ide.conf.
     *
     * @param string $key
     * @param mixed $def
     *
     * @return array
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
     * Задать глобальную настройку для IDE, запишет в конфиг ide.conf.
     *
     * @param $key
     * @param $value
     */
    public function setUserConfigValue($key, $value)
    {
        $this->getUserConfig('ide')->set($key, $value);
    }

    /**
     * Вернуть файл из папки, где находится сама IDE.
     *
     * @param string $path
     *
     * @return File
     */
    public static function getOwnFile($path)
    {
        $homePath = System::getProperty('develnext.path', "./");

        $home = $homePath;

        return File::of("$home/$path");
    }

    /**
     * Вернуть файл из системной папки IDE.
     *
     * @param string $path
     *
     * @return File
     */
    public function getFile($path)
    {
        return IdeSystem::getFile($path);
    }

    /**
     * Вернуть файлы из директории, отвечающие формату.
     *
     * @param AbstractFormat|string $format
     * @param string $directory
     * @return \string[]
     * @throws IllegalArgumentException
     */
    public function getFilesOfFormat($format, $directory)
    {
        if (is_string($format)) {
            $format = $this->getRegisteredFormat($format);
        }

        if (!$format) {
            throw new IllegalArgumentException("Format is invalid");
        }

        $files = [];

        fs::scan($directory, function ($filename) use ($format, &$files) {
            if ($format->isValid($filename)) {
                $files[] = $filename;
            }
        });

        return $files;
    }

    /**
     * Вернуть текущий лог файл IDE.
     *
     * @return File
     */
    public function getLogFile()
    {
        $uuid = $this->getInstanceId();

        return $this->getFile("log/ide-$uuid.log");
    }

    /**
     * Очистить специализированную папку IDE от логов и кэша.
     */
    public function cleanup()
    {
        (new Thread(function() {
            Logger::info("Clean IDE files...");

            fs::scan($this->getFile("log/"), function ($logfile) {
                if (fs::time($logfile) < Time::millis() - 1000 * 60 * 60 * 3) { // 3 hours.
                    fs::delete($logfile);
                }
            });

            fs::scan($this->getFile("cache/"), function ($file) {
                if (fs::time($file) < Time::millis() - 1000 * 60 * 60 * 24 * 30) { // 30 days.
                    fs::delete($file);
                }
            });
        }))->start();
    }

    /**
     * Создать временный файл с специализированной папке IDE.
     *
     * @param string $suffix
     * @return File
     */
    public function createTempFile($suffix = '')
    {
        $tempDir = $this->getFile('tmp');

        if (!fs::isDir($tempDir)) {
            if (fs::exists($tempDir)) {
                fs::delete($tempDir);
            }
        }

        $tempDir->mkdirs();

        $file = File::createTemp(Str::random(5), Str::random(10) . $suffix, $tempDir);
        $file->deleteOnExit();
        return $file;
    }

    /**
     * Вернуть список всех зарегистрированных шаблонов проекта.
     *
     * @return AbstractProjectTemplate[]
     */
    public function getProjectTemplates()
    {
        return $this->projectTemplates;
    }

    /**
     * Зарегистрировать шаблон проекта.
     *
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
     * @param AbstractSettings $settings
     */
    public function registerSettings(AbstractSettings $settings)
    {
        $class = get_class($settings);

        if (isset($this->settings[$class])) {
            return;
        }

        $this->settings[$class] = $settings;
        $settings->onRegister();
    }

    /**
     * @param $class
     */
    public function unregisterSettings($class)
    {
        if ($settings = $this->settings[$class]) {
            $settings->onUnregister();
            unset($this->settings[$class]);
        }
    }

    /**
     * @param bool|true $ignoreAlways
     */
    public function unregisterAllSettings()
    {
        foreach ($this->settings as $settings) {
            $this->unregisterSettings(get_class($settings));
        }
    }

    /**
     * @return AbstractSettings[]
     */
    public function getAllSettings()
    {
        return $this->settings;
    }

    /***
     * @param AbstractNavigation $nav
     */
    public function registerNavigation(AbstractNavigation $nav)
    {
        $this->navigation[reflect::typeOf($nav)] = $nav;
    }

    /**
     * @param $class
     */
    public function unregisterNavigation($class)
    {
        unset($this->navigation[$class]);
    }

    /**
     * Отменить регистрацию одной команды по ее имени класса.
     *
     * @param $commandClass
     * @param bool $ignoreAlways
     */
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
            $menu = $mainForm->findSubMenu('menu' . Str::upperFirst($command->getCategory()));

            if ($menu instanceof UXMenu) {
                foreach ($data['menuItem'] as $el) {
                    $menu->items->remove($el);
                }
            }
        }

        unset($this->commands[$commandClass]);
    }

    /**
     * Отменить регистрацию всех команд.
     */
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
     * Выполнить зарегистрированную команду по названию ее класса.
     *
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
     * Вернуть команду по ее классу.
     *
     * @param string $commandClass class or uid
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
     * Зарегистрировать IDE команду.
     *
     * @param AbstractCommand $command
     * @param null $category
     */
    public function registerCommand(AbstractCommand $command, $category = null)
    {
        $this->unregisterCommand($command->getUniqueId());

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
                        $ui->maxHeight = 9999;
                    } else if ($ui instanceof UXSeparator) {
                        $ui->paddingLeft = 3;
                        $ui->paddingRight = 1;
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
                        $ui->maxHeight = 999;
                    } else if ($ui instanceof UXSeparator) {
                        $ui->paddingLeft = 3;
                        $ui->paddingRight = 1;
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
                $menu = $mainForm->findSubMenu('menu' . Str::upperFirst($category));

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

        $this->commands[$command->getUniqueId()] = $data;
    }

    /**
     * Вернуть изображение из ресурсов IDE /.data/img/
     *
     * @param string $path
     *
     * @param array $size
     * @param bool $cache
     * @return UXImageView
     */
    public static function getImage($path, array $size = null, $cache = true)
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
     * Вернуть зарегистрированный формат по его классу.
     * В приоритете форматы, зарегистирированные в проекте, а уже затем - глобальные форматы.
     *
     * @param $class
     * @return AbstractFormat
     */
    public function getRegisteredFormat($class)
    {
        if ($project = $this->getOpenedProject()) {
            if ($format = $project->getRegisteredFormat($class)) {
               return $format;
            }
        }

        return $this->_getRegisteredFormat($class);
    }

    /**
     * Найти формат для редактирования файла/пути.
     *
     * @param $path
     *
     * @return AbstractFormat|null
     */
    public function getFormat($path)
    {
        if ($project = $this->getOpenedProject()) {
            /** @var AbstractFormat $format */
            foreach (arr::reverse($project->getRegisteredFormats()) as $format) {
                if ($format->isValid($path)) {
                    return $format;
                }
            }
        }

        /** @var AbstractFormat $format */
        foreach (arr::reverse($this->formats) as $format) {
            if ($format->isValid($path)) {
                return $format;
            }
        }

        return null;
    }

    /**
     * Вернуть текущий открытый проект.
     *
     * @return Project
     */
    public function getOpenedProject()
    {
        return $this->openedProject;
    }

    /**
     * Задать открытый проект.
     *
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
     * @deprecated
     * @param string $query
     * @return bool
     */
    public function navigate($query)
    {
        foreach ($this->navigation as $nav) {
            if ($nav->accept($query)) {
                $nav->navigate($query);

                return true;
            }
        }

        return false;
    }

    /**
     * @deprecated
     * @param $query
     * @return callable
     */
    public function nav($query)
    {
        return function () use ($query) {
            $this->navigate($query);
        };
    }

    /**
     * Создать редактор для редактирования файла, формат по-умолчанию определяется автоматически,
     * с помощью ранее зарегистрированных редакторов.
     *
     * @param $path
     *
     * @param array $options
     * @param string $format
     * @return AbstractEditor
     */
    public function createEditor($path, array $options = [], $format = null)
    {
        $format = $format ? $this->getRegisteredFormat($format) : $this->getFormat($path);

        if ($format) {
            $editor = $format->createEditor($path, $options);

            if ($editor) {
                $editor->setFormat($format);
            }

            if ($project = Ide::project()) {
                if (!str::startsWith(FileUtils::hashName($path), FileUtils::hashName($project->getRootDir()))) {
                    $editor->setReadOnly(true);
                }

                $project->trigger('createEditor', $editor);
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

    /**
     * Зарегистрировать расширение IDE (по названию класса или его экземпляру).
     *
     * @param string|AbstractExtension $extension
     * @throws IdeException
     */
    public function registerExtension($extension)
    {
        if (is_string($extension)) {
            $extension = new $extension();
        }

        if (isset($this->extensions[reflect::typeOf($extension)])) {
            return;
        }

        Logger::info("Register IDE extension " . reflect::typeOf($extension));

        if (!($extension instanceof AbstractExtension)) {
            throw new IdeException("Unable to add extension " . reflect::typeOf($extension) . ", is not correct type");
        }

        $this->extensions[reflect::typeOf($extension)] = $extension;

        foreach ((array) $extension->getDependencies() as $class) {
            $dep = new $class();

            if ($dep instanceof AbstractBundle) {
                IdeSystem::getLoader()->addClassPath($dep->getVendorDirectory());
            } else {
                $this->registerExtension($extension);
            }
        }

        $extension->onRegister();
    }

    public function registerAll()
    {
        $this->cleanup();

        $extensions = $this->getInternalList('.dn/extensions');

        foreach ($extensions as $extension) {
            $this->registerExtension($extension);
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

        $navs = $this->getInternalList('.dn/navigation');
        foreach ($navs as $nav) {
            $this->registerNavigation(new $nav());
        }

        $projectTemplates = $this->getInternalList('.dn/projectTemplates');
        foreach ($projectTemplates as $projectTemplate) {
            $this->registerProjectTemplate(new $projectTemplate());
        }

        $this->registerSettings(new AllSettings());

        $mainCommands = $this->getInternalList('.dn/mainCommands');
        $commands = [];
        foreach ($mainCommands as $commandClass) {
            /** @var AbstractCommand $command */
            $command = new $commandClass();

            $commands[] = $command;
        }

        $commands = arr::sort($commands, function (AbstractCommand $a, AbstractCommand $b) {
            if ($a->getPriority() == $b->getPriority()) { return 0; }
            if ($a->getPriority() < $b->getPriority()) { return 1; }
            return -1;
        });

        foreach ($commands as $command) {
            $this->registerCommand($command);
        }

        $this->library->update();

        /** @var AccurateTimer $inactiveTimer */
        $inactiveTimer = new AccurateTimer(5 * 60 * 1000, function () {
            $this->idle = true;
            Logger::info("IDE is sleeping, idle mode ...");
            $this->trigger('idleOn');
        });
        $inactiveTimer->start();

        $this->getMainForm()->addEventFilter('mouseMove', function () use (&$inactiveTimer) {
            if ($inactiveTimer) {
                $inactiveTimer->reset();
            }

            if ($this->idle) {
                Logger::info("IDE awake, idle mode = off ...");
                $this->trigger('idleOff');
            }

            $this->idle = false;
        });

        $ideConfig = $this->getUserConfig('ide');

        if (!fs::isDir($ideConfig->get('projectDirectory'))) {
            $ideConfig->set('projectDirectory', File::of(System::getProperty('user.home') . '/DevelNextProjects/'));
        }


        /*$manager = new UXKeyboardManager($this->getMainForm());
        $manager->onDown('Ctrl+Tab', function (UXKeyEvent $e) {
            uiLater(function () {
                FileSystem::openNext();
            });
        });*/

        $this->afterShow(function () {
            $projectFile = $this->getUserConfigValue('lastProject');

            FileSystem::open('~welcome');

            if (!$this->disableOpenLastProject && $projectFile && File::of($projectFile)->exists()) {
                ProjectSystem::open($projectFile, false);
            }
        });
    }

    /**
     * Добавить коллбэка, выполняющийся после старта и показа IDE.
     * Если IDE уже была показана, коллбэк будет выполнен немедленно.
     *
     * @param callable $handle
     */
    public function afterShow(callable $handle)
    {
        if ($this->isLaunched()) {
            $handle();
        } else {
            $this->afterShow[] = $handle;
        }
    }

    /**
     * Находится ли IDE в спящем режиме (т.е. не используется).
     * @return boolean
     */
    public function isIdle()
    {
        return $this->idle;
    }

    /**
     * Вернуть главную форму.
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
     * Вернуть открытый проект.
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
     * Показать toast сообщение на главной форме IDE.
     *
     * @param $text
     * @param int $timeout
     */
    public static function toast($text, $timeout = 0)
    {
        Ide::get()->getMainForm()->toast($text, $timeout);
    }

    /**
     * Завершить работу IDE.
     */
    public function shutdown()
    {
        $done = false;

        $shutdownTh = (new Thread(function () use (&$done) {
            sleep(40);

            while (!$done) {
                sleep(1);
            }

            Logger::warn("System halt 0\n");
            System::halt(0);
        }));

        $shutdownTh->setName("DevelNext Shutdown");
        $shutdownTh->start();

        Logger::info("Start IDE shutdown ...");

        $this->trigger(__FUNCTION__);

        (new Thread(function () {
            Logger::info("Shutdown asyncThreadPool");
            $this->asyncThreadPool->shutdown();
        }))->start();

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

        foreach ($this->configurations as $name => $config) {
            if ($config->isAutoSave()) {
                $config->save();
            }
        }

        if ($project) {
            FileSystem::getSelectedEditor()->save();
            ProjectSystem::close(false);
        }

        //WatcherSystem::shutdown();

        Logger::info("Finish IDE shutdown");

        try {
            Logger::shutdown();
            parent::shutdown();
        } catch (\Exception $e) {
            //System::halt(0);
        }

        $done = true;
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

        if (!$resources) {
            Logger::warn("Internal list '$resourceName' is empty");
        }

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
     * @param $argv
     * @return bool
     */
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

    /**
     * Отключить открытие последнего редактируемого проекта.
     * Можно применять при старте IDE, чтобы отменить загрузку предыдущего проекта.
     */
    public function disableOpenLastProject()
    {
        $this->disableOpenLastProject = true;
    }

    /**
     * Сравнить версию с версией IDE.
     * @param $otherVersion
     * @return bool
     */
    public function isSameVersionIgnorePatch($otherVersion)
    {
        $version = IdeSystem::getVersionInfo($this->getVersion());
        $otherVersion = IdeSystem::getVersionInfo($otherVersion);

        $result = $otherVersion['type'] === $version['type'] && $otherVersion['major'] == $version['major']
            && $otherVersion['minor'] === $version['minor'];

        Logger::debug("isSameVersionIgnorePatch(): " . Json::encode($version) . " with " . Json::encode($otherVersion)
            . " is " . ($result ? 'true' : 'false'));

        return $result;
    }

    /**
     * Restart IDE, запустить рестарт IDE, работает только в production режиме.
     */
    public function restart()
    {
        $jrePath = $this->getJrePath();

        $javaBin = 'java';

        if ($jrePath) {
            $javaBin = "$jrePath/bin/$javaBin";
        }

        $process = new Process([$javaBin, '-jar', 'DevelNext.jar'], IdeSystem::getOwnFile(''), $this->makeEnvironment());
        $process->start();

        if (Ide::project()) {
            Ide::get()->setUserConfigValue('lastProject', Ide::project()->getProjectFile());
        }

        $this->shutdown();
    }
}