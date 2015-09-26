<?php
namespace ide\project;
use Exception;
use Files;
use ide\formats\AbstractFileTemplate;
use ide\forms\MainForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\systems\FileSystem;
use ide\systems\WatcherSystem;
use ide\utils\FileHelper;
use ide\utils\FileUtils;
use php\compress\ArchiveInputStream;
use php\compress\ArchiveOutputStream;
use php\io\File;
use php\io\FileStream;
use php\io\Stream;
use php\lib\Items;
use php\lib\Str;
use php\time\Time;
use php\util\Flow;

/**
 * Class Project
 * @package ide\project
 */
class Project
{
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

        $this->tree = new ProjectTree($this, $mainForm->getProjectTree());
        $this->indexer = new ProjectIndexer($this);
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
     * @param string $path
     *
     * @return bool
     */
    public function makeDirectory($path)
    {
        $directory = "$this->rootDir/$path";

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
     * @return File
     */
    public function getIdeDir()
    {
        return File::of("$this->rootDir/.dn");
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
        $this->trigger(__FUNCTION__);
    }

    /**
     * Вызвать в момент открытия проекта (после загрузки, создания и восстановления).
     */
    public function open()
    {
        $this->tree->clear();

        $this->trigger(__FUNCTION__);

        $this->tree->update();

        foreach ($this->config->getOpenedFiles() as $file) {
            if ($this->getFile($file)->exists()) {
                $file = $this->getFile($file);
            } else {
                $file = $this->getAbsoluteFile($file);
            }

            if (File::of($file)->exists()) {
                FileSystem::open($file, false);
            }
        }

        $selected = $this->config->getSelectedFile();

        if ($this->getFile($selected)->exists()) {
            $selected = $this->getFile($selected);
        }

        if ($selected && File::of($selected)->exists()) {
            FileSystem::open($selected, true);
        }

        //if (!$this->indexer->isValid()) { todo implement it
            $this->reindex();
        //  }
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
     * @param $file
     */
    public function export($file)
    {
        $exporter = new ProjectExporter($this, $file);
        $exporter->addFile($this->getProjectFile());
        $exporter->addDirectory($this->getIdeDir());
        $exporter->removeFile($this->indexer->getIndexFile());

        $this->trigger(__FUNCTION__, $exporter);

        $exporter->save();
    }

    /**
     * Загрузить данные проекта.
     */
    public function load()
    {
        $dir = $this->getIdeDir();

        if (!$dir->isDirectory()) {
            $dir->mkdirs();
        }

        $this->filesData  = $this->config->createFiles($this);
        $this->behaviours = $this->config->createBehaviours($this);
        $this->template   = $this->config->getTemplate();

        $this->tree->clear();

        $this->trigger(__FUNCTION__);
    }

    /**
     * Сохранить все данные проекта.
     */
    public function save()
    {
        $this->trigger(__FUNCTION__);

        FileSystem::saveAll();

        $files = Flow::of(FileSystem::getOpened())->map(function ($e) { return $this->getAbsoluteFile($e['file']); })->toArray();

        $this->config->setOpenedFiles($files, FileSystem::getSelected());
        $this->config->setProjectFiles($this->filesData);
        $this->config->setBehaviours($this->behaviours);

        $this->config->setProject($this);

        $this->config->save();
    }

    /**
     * Восстановить целостность файлов проекта.
     */
    public function recover()
    {
        $this->trigger(__FUNCTION__);
    }

    /**
     * @param string $environment dev, prod, test, etc.
     * @param callable $log
     */
    public function compile($environment, callable $log = null)
    {
        $this->save();

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
     * @return AbstractProjectBehaviour
     */
    public function register($any)
    {
        if ($any instanceof AbstractProjectBehaviour) {
            return $this->behaviours[get_class($any)] = $any->forProject($this);
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

    public function close()
    {
        $this->save();
        $this->tree->clear(true);
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
}