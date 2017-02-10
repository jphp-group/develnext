<?php
namespace ide\project;
use ide\utils\FileUtils;
use php\gui\designer\UXDirectoryTreeValue;
use php\gui\designer\UXDirectoryTreeView;
use php\gui\designer\UXFileDirectoryTreeSource;
use php\io\File;
use php\lib\fs;


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
    }

    /**
     * @return UXFileDirectoryTreeSource
     */
    public function createSource()
    {
        $source = new UXFileDirectoryTreeSource($this->project->getRootDir());

        $ignoresPath = [
            '.dn' => 1,
            'application.pid' => 1,
            'src_generated' => 1,
            'build.gradle' => 1,
            'settings.gradle' => 1,
            'build.xml' => 1,
            'src/JPHP-INF' => 1,
            'src/.debug' => 1,
            'src/.system' => 1,
            'src/.scripts' => 1,
            'src/.forms' => 1,
        ];

        $ignoresExt = [
            'behaviour' => 1,
            'axml' => 1,
            'fxml' => 1
        ];

        $source->addFileFilter(function (File $file) use ($source, $ignoresPath, $ignoresExt) {
            $ext = fs::ext($file);

            if ($ignoresExt[$ext]) {
                return false;
            }

            $path = FileUtils::relativePath($source->getDirectory(), $file);

            if ($ignoresPath[$path]) {
                return false;
            }

            return true;
        });

        $source->addValueCreator(function ($path, File $file) {
            if (fs::ext($path) == 'dnproject') {
                return new UXDirectoryTreeValue($path, fs::name($path), fs::name($path), ico('myProject16'), null, $file->isDirectory());
            }
        });

        return $source;
    }
}