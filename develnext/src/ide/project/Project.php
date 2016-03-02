<?php
namespace ide\project;
use Exception;
use Files;
use ide\formats\AbstractFileTemplate;
use ide\forms\MainForm;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\Logger;
use ide\misc\AbstractCommand;
use ide\systems\FileSystem;
use ide\systems\WatcherSystem;
use ide\utils\FileHelper;
use ide\utils\FileUtils;
use php\compress\ArchiveInputStream;
use php\compress\ArchiveOutputStream;
use php\gui\UXApplication;
use php\gui\UXTreeView;
use php\io\File;
use php\io\FileStream;
use php\io\IOException;
use php\io\Stream;
use php\lib\arr;
use php\lib\Items;
use php\lib\Str;
use php\time\Time;
use php\util\Configuration;
use php\util\Flow;
use script\TimerScript;

/**
 * Class Project
 * @package ide\project
 */
class Project
{
    const ENV_ALL  = 'all';
    const ENV_DEV  = 'dev';
    const ENV_PROD = 'prod';
    const ENV_TEST = 'test';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $rootDir;

    /**
     * @var string[]
     */
    protected $sourceRoots = [];

    /**
     * @var AbstractProjectBehaviour[]
     */
    protected $behaviours = [];

    /**
     * @var array
     */
    protected $handlers = [];

    /**
     * @var ProjectFile[]
     */
    protected $filesData = [];

    /**
     * @var array
     */
    protected $ignoreRules = [];

    /**
     * @var ProjectConfig
     */
    protected $config;

    /**
     * @var Configuration[]
     */
    protected $ideConfigs = [];

    /**
     * @var ProjectTree
     */
    protected $tree;

    /**
     * @var AbstractProjectTemplate
     */
    protected $template;

    /**
     * @var ProjectIndexer
     */
    protected $indexer;

    /**
     * @var ProjectRefactorManager
     */
    protected $refactorManager;

    /**
     * @var TimerScript
     */
    protected $tickTimer;

    /**
     * @var string
     */
    protected $srcDirectory = null;

    /**
     * @var string
     */
    protected $srcGeneratedDirectory = null;

    /**
     * @var string
     */
    protected $resDirectory = null;

    /**
     * Project constructor.
     *
     * @param string $rootDir
     * @param string $name
     */
    public function __construct($rootDir, $name)
    {
        $this->name = $name;
        $this->rootDir = $rootDir;
        $this->config  = new ProjectConfig($rootDir, $name);

        /** @var MainForm $mainForm */
        $mainForm = Ide::get()->getMainForm();

        $this->tree = new ProjectTree($this, new UXTreeView());
        $this->indexer = new ProjectIndexer($this);
        $this->refactorManager = new ProjectRefactorManager($this);

        $this->tickTimer = new TimerScript(1000 * 9, true, [$this, 'doTick']);
    }

    /**
     * @param string $filename
     *
     * @return Project
     */
    public static function createForFile($filename)
    {
        $file = File::of($filename);

        $name = $file->getName();

        if (Str::endsWith($name, '.dnproject')) {
            $name = Str::sub($name, 0, Str::length($name) - 10);
        }

        return new Project($file->getParent(), $name);
    }

    public function doTick()
    {
        $file = $this->getIdeFile("ide.lock");
        FileUtils::put($file, Time::millis());
    }

    public function getProjectFile()
    {
        return $this->getFile($this->name . ".dnproject");
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $newName
     * @return bool
     */
    public function setName($newName)
    {
        if (FileUtils::copyFile($this->getProjectFile(), $this->getFile($newName . ".dnproject")) == -1) {
            return false;
        }

        $this->trigger('changeName', $this->name, $newName);

        $this->getProjectFile()->delete();
        $this->getProjectFile()->deleteOnExit();

        $this->name = $newName;

        return true;
    }

    /**
     * @return string
     */
    public function getSrcDirectory()
    {
        return $this->srcDirectory;
    }

    /**
     * @param string $srcDirectory
     */
    public function setSrcDirectory($srcDirectory)
    {
        $this->srcDirectory = $srcDirectory;
    }

    /**
     * @return string
     */
    public function getSrcGeneratedDirectory()
    {
        return $this->srcGeneratedDirectory;
    }

    /**
     * @param string $srcGeneratedDirectory
     */
    public function setSrcGeneratedDirectory($srcGeneratedDirectory)
    {
        $this->srcGeneratedDirectory = $srcGeneratedDirectory;
    }

    /**
     * @return string
     */
    public function getResDirectory()
    {
        return $this->resDirectory;
    }

    /**
     * @param string $resDirectory
     */
    public function setResDirectory($resDirectory)
    {
        $this->resDirectory = $resDirectory;
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public function makeDirectory($path)
    {
        $directory = "$this->rootDir/$path";

        Logger::info("Make directory in project: $directory");

        return File::of($directory)->mkdirs();
    }

    /**
     * @param $file
     * @param AbstractFileTemplate $template
     *
     * @return ProjectFile
     */
    public function createFile($file, AbstractFileTemplate $template)
    {
        $file = $this->getFile($file);

        $file->applyTemplate($template);
        $file->updateTemplate(true);

        return $file;
    }

    /**
     * @param string $file
     * @param AbstractFileTemplate $template
     */
    public function defineFile($file, AbstractFileTemplate $template)
    {
        $file = $this->getFile($file);

        if ($file->isNew()) {
            $file->setGenerated(true);
            $file->applyTemplate($template);
        }

        $file->updateTemplate();
    }

    /**
     * @param string $file
     * @return ProjectFile|File
     */
    public function getFile($file)
    {
        return $this->fetchFile("$this->rootDir/$file");
    }

    /**
     * @param string $file
     * @param bool $generated
     * @return ProjectFile|File
     * @throws Exception
     */
    public function getSrcFile($file, $generated = false)
    {
        $srcDirectory = $generated ? $this->srcGeneratedDirectory : $this->srcDirectory;

        if ($srcDirectory === null) {
            throw new Exception(($generated ? "srcGeneratedDirectory" : "srcDirectory") . " is not set");
        }

        return $srcDirectory ? $this->getFile("$srcDirectory/$file") : $this->getFile($file);
    }

    /**
     * @param $file
     *
     * @return ProjectFile|File
     */
    public function getAbsoluteFile($file)
    {
        return $this->fetchFile("$file");
    }

    /**
     * @return array
     */
    public function getIgnoreRules()
    {
        return $this->ignoreRules;
    }

    /**
     * @param array $ignoreRules
     */
    public function setIgnoreRules(array $ignoreRules)
    {
        $this->ignoreRules = $ignoreRules;
    }

    /**
     * @param string $root path
     */
    public function addSourceRoot($root)
    {
        $this->sourceRoots[FileUtils::hashName($root)] = $root;
    }

    /**
     * @param string $root path
     */
    public function removeSourceRoot($root)
    {
        $this->sourceRoots[FileUtils::hashName($root)] = $root;
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * @return Configuration
     */
    public function getIdeServiceConfig()
    {
        return $this->getIdeConfig('project.ws');
    }

    public function getIdeLibraryConfig()
    {
        return $this->getIdeConfig('library.conf');
    }

    /**
     * @param $name
     * @return Configuration
     */
    public function getIdeConfig($name)
    {
        if ($configuration = $this->ideConfigs[$name]) {
            return $configuration;
        }

        $configuration = new Configuration();

        try {
            $configuration->load($this->getIdeDir() . "/$name");
        } catch (IOException $e) {
            ;
        }

        return $this->ideConfigs[$name] = $configuration;
    }

    /**
     * @param $name
     * @param Configuration $configuration
     */
    public function setIdeConfig($name, Configuration $configuration)
    {
        Logger::info("Save ide config ($name) of project ...");

        if (File::of($name)->exists()) {
            // ignore...
            return;
        }

        try {
            $file = $this->getIdeFile("$name");

            if ($file->isDirectory()) {
                $file->delete();
            }

            if ($file->getParentFile()) {
                $file->getParentFile()->mkdirs();
            }

            $configuration->save($file);
        } catch (IOException $e) {
            Logger::error("Unable to save ide config $name");
        }
    }

    /**
     * @return File
     */
    public function getIdeDir()
    {
        return File::of("$this->rootDir/.dn");
    }

    /**
     * @param $name
     * @return File
     */
    public function getIdeFile($name)
    {
        return new File($this->getIdeDir(), "/$name");
    }

    /**
     * @return string[]
     */
    public function getSourceRoots()
    {
        return Flow::of([$this->rootDir])->append($this->sourceRoots)->toArray();
    }

    /**
     * @param string $event
     * @param callable $callback
     */
    public function on($event, callable $callback)
    {
        $this->handlers[$event][] = $callback;
    }

    /**
     * @param $event
     * @param ...$args
     */
    public function trigger($event, ...$args)
    {
        foreach ((array) $this->handlers[$event] as $handler) {
            if ($handler(...$args)) {
                break;
            }
        }
    }

    /**
     * @param $type
     *
     * @return bool
     */
    public function hasBehaviour($type)
    {
        return isset($this->behaviours[$type]);
    }

    /**
     * @param string $type
     *
     * @return AbstractProjectBehaviour
     * @throws Exception
     */
    public function getBehaviour($type)
    {
        $behaviour = $this->behaviours[$type];

        if (!$behaviour) {
            throw new Exception('The "' . $type . '" behaviour is not registered');
        }

        return $behaviour;
    }

    /**
     * @return ProjectIndexer
     */
    public function getIndexer()
    {
        return $this->indexer;
    }

    /**
     * @return ProjectRefactorManager
     */
    public function getRefactorManager()
    {
        return $this->refactorManager;
    }

    /**
     * @return ProjectTree
     */
    public function getTree()
    {
        return $this->tree;
    }

    /**
     * Вызывать при создании проекта.
     */
    public function create()
    {
        FileSystem::open('~project');
        $this->trigger(__FUNCTION__);
    }

    /**
     * Вызывать при смене табов.
     */
    public function update()
    {
        $this->trigger(__FUNCTION__);

        $this->tree->update();
    }

    /**
     * Вызвать в момент открытия проекта (после загрузки, создания и восстановления).
     */
    public function open()
    {
        Logger::info("Opening project ...");

        $this->tree->clear();

        $this->trigger(__FUNCTION__);

        $this->tree->update();

        //if (!$this->indexer->isValid()) { todo implement it
        $this->reindex();
        //  }

        foreach ($this->config->getOpenedFiles() as $file) {
            if ($this->getFile($file)->exists()) {
                $file = $this->getFile($file);
            } else {
                $file = $this->getAbsoluteFile($file);
            }

            if (File::of($file)->exists()) {
                //UXApplication::runLater(function () use ($file) {
                    FileSystem::open($file, false);
                //});
            }
        }

        $selected = $this->config->getSelectedFile();

        if ($this->getFile($selected)->exists()) {
            $selected = $this->getFile($selected);
        }

        if ($selected && File::of($selected)->exists()) {
            uiLater(function () use ($selected) {
                FileSystem::open($selected, true);
            });
        }

        $this->doTick();

        $this->tickTimer->start();
    }

    /**
     * Переиндексировать весь проект.
     */
    public function reindex()
    {
        $this->indexer->clear();

        $this->trigger(__FUNCTION__, $this->indexer);

        $this->indexer->save();
    }

    /**
     * @return ProjectExporter
     * @throws \php\lang\IllegalArgumentException
     */
    public function makeExporter()
    {
        $exporter = new ProjectExporter($this);
        $exporter->addDirectory($this->getIdeDir());
        $exporter->addFile($this->getProjectFile());
        $exporter->removeFile($this->indexer->getIndexFile());
        $exporter->removeFile($this->getIdeLibraryConfig());
        $exporter->removeFile($this->getIdeFile("ide.lock"));

        $this->trigger('export', $exporter);

        return $exporter;
    }

    /**
     * @param $file
     */
    public function export($file)
    {
        Logger::info("Project export to: $file");

        $this->makeExporter()->save($file);
    }

    /**
     * Загрузить данные проекта.
     */
    public function load()
    {
        Logger::info("Project loading ...");

        $dir = $this->getIdeDir();

        if (!$dir->isDirectory()) {
            $dir->mkdirs();
        }

        $this->filesData  = $this->config->createFiles($this);
        $this->template   = $this->config->getTemplate();

        $this->config->createBehaviours($this);

        if ($this->template) {
            $this->template->recoveryProject($this);
        } else {
            throw new InvalidProjectFormatException("Unable to fetch template project");
        }

        $this->behaviours = arr::sort($this->behaviours, function (AbstractProjectBehaviour $a, AbstractProjectBehaviour $b) {
            if ($a->getPriority() > $b->getPriority()) {
                return 1;
            } else {
                if ($a->getPriority() < $b->getPriority()) {
                    return -1;
                }

                return 0;
            }
        }, true);

        foreach ($this->behaviours as $behaviour) {
            $behaviour->inject();
        }

        $this->tree->clear();

        $this->trigger(__FUNCTION__);
    }

    public function saveIdeConfig($name)
    {
        $config = $this->ideConfigs[$name];

        if ($config) {
            $this->setIdeConfig($name, $config);
        }
    }

    /**
     * Сохранить все данные проекта.
     */
    public function save()
    {
        Logger::info("Start project saving ...");

        $this->trigger(__FUNCTION__);

        FileSystem::saveAll();

        foreach ($this->ideConfigs as $name => $config) {
            $this->setIdeConfig($name, $config);
        }

        $files = Flow::of(FileSystem::getOpened())->map(function ($e) { return $this->getAbsoluteFile($e['file']); })->toArray();

        $this->config->setOpenedFiles($files, FileSystem::getSelected());
        $this->config->setProjectFiles($this->filesData);
        $this->config->setBehaviours($this->behaviours);

        $this->config->setProject($this);

        $this->config->save();

        Logger::info("Project is saved.");
    }

    /**
     * Восстановить целостность файлов проекта.
     */
    public function recover()
    {
        $this->trigger(__FUNCTION__);
    }

    /**
     * @param $environment
     * @param callable|null $log
     */
    public function preCompile($environment, callable $log = null)
    {
        Logger::info("Precompile project: env = $environment");

        $this->trigger(__FUNCTION__, $environment, $log);
    }

    /**
     * @param string $environment dev, prod, test, etc.
     * @param callable $log
     */
    public function compile($environment, callable $log = null)
    {
        $this->save();

        Logger::info("Compile project: env = $environment");

        $this->trigger(__FUNCTION__, $environment, $log);
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public function isIgnoredPath($path)
    {
        foreach ($this->ignoreRules as $rule) {
            if (File::of($path)->matches($rule)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $file
     *
     * @return bool
     */
    public function isContainsFile($file)
    {
        $hash = FileUtils::hashName($file);

        foreach ($this->getSourceRoots() as $root) {
            $rootHash = FileUtils::hashName($root);

            if (Str::pos($hash, $rootHash) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $file
     *
     * @return bool
     */
    public function isSynchronizedFile($file)
    {
        $file = FileUtils::hashName($file);

        $data = $this->filesData[$file];

        return $data && !$data->isChanged();
    }

    /**
     * Синхронизирует информацию о файле, вызывая события изменения файла.
     *
     * @param string $file
     */
    public function synchronizeFile($file)
    {
        if (!$this->isIgnoredPath($file) && FileSystem::isOpened($file) && !$this->isSynchronizedFile($file)) {
            $this->trigger(__FUNCTION__, $file);

            $this->fetchFile($file)->setSyncTime(Time::millis());
        }
    }

    /**
     * @param string $path
     */
    public function synchronizePath($path)
    {
        FileUtils::scan($path, function ($filename) {
            $this->synchronizeFile($filename);
        });
    }

    /**
     * @param $any
     *
     * @param bool $inject
     * @return AbstractProjectBehaviour
     */
    public function register($any, $inject = true)
    {
        if ($any instanceof AbstractProjectBehaviour) {
            return $this->behaviours[get_class($any)] = $any->forProject($this, $inject);
        } else {
            throw new \InvalidArgumentException("Unable to register an instance of class " . get_class($any));
        }
    }

    /**
     * @param $file
     *
     * @return ProjectFile
     */
    protected function fetchFile($file)
    {
        $hash = FileUtils::hashName($file);

        if (!($o = $this->filesData[$hash])) {
            return $this->filesData[$hash] = new ProjectFile($this, $file);
        }

        return $o;
    }

    /**
     * @param AbstractProjectTemplate $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return AbstractProjectTemplate
     */
    public function getTemplate()
    {
        return $this->template;
    }

    public function close($save = true)
    {
        Logger::info("Close project ...");

        $this->tickTimer->stop();

        $file = $this->getIdeFile("ide.lock");
        $file->delete();
        $file->deleteOnExit();

        if ($save) {
            $this->save();
        }

        $this->ideConfigs = [];
        $this->tree->clear(true);
    }

    /**
     * @param string $fileName
     * @return ProjectFile[]
     */
    public function findDuplicatedFiles($fileName)
    {
        $file = File::of($fileName);

        $length = $file->length();
        $crc  = $file->crc32();
        $hash = $file->hash('SHA-256');

        $duplicates = [];

        FileUtils::scan($this->getFile('src/'), function ($filename) use ($crc, $length, $hash, &$duplicates) {
            $file = File::of($filename);

            if (!$file->isFile()) {
                return;
            }

            if ($file->length() !== $length) {
                return;
            }

            if ($file->crc32() !== $crc) {
                return;
            }

            if ($file->hash('SHA-256') !== $hash) {
                return;
            }

            $duplicates[] = $this->getAbsoluteFile($filename);
        });

        return $duplicates;
    }

    /**
     * @param $fileName
     * @param $directory
     * @return ProjectFile
     */
    public function copyFile($fileName, $directory)
    {
        $file = File::of($fileName);
        $name = $file->getName();

        $directory = $this->getFile($directory);

        $x = 2;

        while (Files::exists($directory . '/' . $name)) {
            $name = FileUtils::stripExtension($file->getName()) . ($x++) . '.' . FileUtils::getExtension($file->getName());
        }

        $newFile = "$directory/$name";

        FileUtils::copyFile($fileName, $newFile);

        return $this->getAbsoluteFile($newFile);
    }

    public function isOpenedInOtherIde()
    {
        $lockFile = $this->getIdeFile("ide.lock");

        if ($lockFile->exists()) {
            $pid = FileUtils::get($lockFile);

            if ($pid) {
                if ($pid > Time::millis() - 15 * 1000) {
                    return true;
                }
            }
        }

        return false;
    }
}