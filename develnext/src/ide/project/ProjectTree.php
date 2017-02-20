<?php
namespace ide\project;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\Logger;
use ide\systems\FileSystem;
use ide\systems\IdeSystem;
use ide\utils\FileUtils;
use php\gui\designer\UXDirectoryTreeValue;
use php\gui\designer\UXDirectoryTreeView;
use php\gui\designer\UXFileDirectoryTreeSource;
use php\gui\event\UXDragEvent;
use php\gui\event\UXMouseEvent;
use php\gui\UXDesktop;
use php\io\File;
use php\lang\Process;
use php\lib\fs;
use php\lib\str;


/**
 * Class ProjectTree
 * @package ide\project
 */
class ProjectTree
{
    /**
     * @var Project
     */
    protected $project;

    /**
     * @var UXDirectoryTreeView
     */
    protected $tree;

    /**
     * @var array
     */
    protected $ignoreExts = [];

    /**
     * @var array
     */
    protected $ignorePaths = ['.dn' => 1];

    /**
     * @var callable[]
     */
    protected $ignoreFilters = [];

    /**
     * @var callable[]
     */
    protected $openHandlers = [];

    /**
     * @var callable[]
     */
    protected $valueCreators = [];

    /**
     * ProjectTree constructor.
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * @param UXDirectoryTreeView $treeView
     */
    public function setView(UXDirectoryTreeView $treeView)
    {
        $this->tree = $treeView;

        $treeView->on('dragOver', function (UXDragEvent $e) {
            if ($e->dragboard->files) {
                $e->acceptTransferModes(['MOVE', 'COPY']);
                $e->consume();
            }
        });

        $treeView->on('dragDrop', function (UXDragEvent $e) use ($treeView) {
            if ($e->dragboard->files && $treeView->focusedItem) {

                $item = $treeView->focusedItem;

                if ($item->value) {
                    $destFile = $this->project->getFile($item->value->path);

                    if ($destFile->isFile()) {
                        $destFile = $destFile->getParentFile();
                    }

                    foreach ($e->dragboard->files as $file) {
                        $copyFile = $destFile->getPath() . "/" . fs::name($file);

                        if (FileUtils::equalNames($file, $copyFile)) {
                            continue;
                        }

                        if ($file->isFile()) {
                            FileUtils::copyFile($file, $copyFile);
                        } else {
                            fs::makeDir($copyFile);
                            FileUtils::copyDirectory($file, $copyFile);
                        }

                        // in root dir of project
                        if (str::startsWith(FileUtils::hashName($file), FileUtils::hashName($this->project->getRootDir()))) {
                            if ($file->isDirectory()) {
                                FileUtils::deleteDirectory($file);
                            } else {
                                fs::delete($file);
                            }
                        }
                    }

                    $e->consume();
                }
            }
        });

        $treeView->on('click', function (UXMouseEvent $e) use ($treeView) {
            if ($e->clickCount > 1) {
                if ($treeView->selectedItems && $treeView->selectedItems[0]->value instanceof UXDirectoryTreeValue) {
                    $file = $this->project->getFile($treeView->selectedItems[0]->value->path);

                    if ($file->isFile()) {
                        foreach ($this->openHandlers as $handler) {
                            if ($handler($file)) {
                                return;
                            }
                        }

                        $editor = FileSystem::open($file);

                        if (!$editor) {
                            switch (fs::ext($file)) {
                                case 'png':
                                case 'jpg':
                                case 'jpeg':
                                case 'bmp':
                                case 'gif':
                                case 'ico':
                                case 'wav':
                                case 'ogg':
                                case 'wave':
                                case 'mp3':
                                case 'aif':
                                case 'aiff':
                                case 'zip':
                                case 'rar':
                                case '7z':
                                case 'mp4':
                                case 'flv':
                                    open($file);
                                    break;

                                case 'ini':
                                case 'conf':
                                case 'txt':
                                case 'log':
                                case 'js':
                                case 'html':
                                case '':
                                    $desktop = new UXDesktop();
                                    $desktop->edit($file);
                                    break;

                                case 'exe':
                                    if (Ide::get()->isWindows() && MessageBoxForm::confirm("Запустить исполняемый файл " . fs::name($file) . '?')) {
                                        execute($file);
                                    }
                                    break;

                                case 'bat':
                                    if (Ide::get()->isWindows() && MessageBoxForm::confirm("Запустить исполняемый файл " . fs::name($file) . '?')) {
                                        $process = new Process(['cmd', '/c', $file], fs::parent($file), (array)$_ENV);
                                        $process->start();
                                        return;
                                    }

                                    if (!Ide::get()->isWindows()) {
                                        $desktop = new UXDesktop();
                                        $desktop->edit($file);
                                        return;
                                    }

                                    break;

                                default:
                                    if (MessageBoxForm::confirm("Файл невозможно открыть в DevelNext, открыть его через системный редактор?")) {
                                        $desktop = new UXDesktop();
                                        $desktop->open($file);
                                    }
                            }

                        }
                    }
                }
            }
        }, __CLASS__);
    }

    public function getExpandedPaths()
    {
        return $this->tree ? $this->tree->expandedPaths : [];
    }

    /**
     * @param array $exts
     */
    public function addIgnoreExtensions(array $exts)
    {
        foreach ($exts as $ext) {
            $this->ignoreExts[$ext] = 1;
        }
    }

    public function addIgnorePaths(array $paths)
    {
        foreach ($paths as $path) {
            $this->ignorePaths[$path] = 1;
        }
    }

    public function addIgnoreFilter(callable $callback)
    {
        $this->ignoreFilters[] = $callback;
    }

    public function addValueCreator(callable $callback)
    {
        $this->valueCreators[] = $callback;
    }

    public function addOpenHandler(callable $callback)
    {
        $this->openHandlers[] = $callback;
    }

    /**
     * @return UXFileDirectoryTreeSource
     */
    public function createSource()
    {
        $source = new UXFileDirectoryTreeSource($this->project->getRootDir());

        $source->addFileFilter(function (File $file) use ($source) {
            $ext = fs::ext($file);

            if ($this->ignoreExts[$ext]) {
                return false;
            }

            if ($this->ignorePaths) {
                $path = FileUtils::relativePath($source->getDirectory(), $file);

                if ($this->ignorePaths[$path]) {
                    return false;
                }
            }

            if ($this->ignoreFilters) {
                foreach ($this->ignoreFilters as $filter) {
                    if ($filter($file)) {
                        return false;
                    }
                }
            }

            return true;
        });

        $ide = Ide::get();

        foreach ($this->valueCreators as $creator) {
            $source->addValueCreator($creator);
        }

        $source->addValueCreator(function ($path, File $file) use ($ide) {
            $format = $ide->getFormat($file);

            if ($format) {
                return new UXDirectoryTreeValue($path, fs::name($path), fs::name($path), $ide->getImage($format->getIcon()), null, $file->isDirectory());
            }
        });

        return $source;
    }

    public function setExpandedPaths(array $paths)
    {
        $this->tree->expandedPaths = $paths;
    }
}