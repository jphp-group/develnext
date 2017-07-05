<?php
namespace ide\project;
use ide\utils\FileUtils;
use php\compress\ArchiveOutputStream;
use php\compress\ZipFile;
use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lang\IllegalArgumentException;
use php\lib\fs;
use php\lib\Str;

/**
 * Class ProjectExporter
 * @package ide\project
 */
class ProjectExporter
{
    /**
     * @var string[] name -> file
     */
    protected $files = [];

    /**
     * @var Project
     */
    private $project;

    /**
     * ProjectExporter constructor.
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * @param $file
     * @throws IllegalArgumentException
     */
    public function addFile($file)
    {
        $file = $this->project->getAbsoluteFile($file);

        if (!$file->isInRootDir()) {
            throw new IllegalArgumentException("Unable to add file that is not in root directory of the project");
        }

        $this->files[$file->getRelativePath()] = $file;
    }

    /**
     * @param $file
     */
    public function removeFile($file)
    {
        if (fs::isDir($file)) {
            $this->removeDirectory($file);
            return;
        }

        $file = $this->project->getAbsoluteFile($file);
        unset($this->files[$file->getRelativePath()]);
    }

    /**
     * @param $dir
     */
    public function removeDirectory($dir)
    {
        $file = $this->project->getAbsoluteFile($dir);

        fs::scan($file, function ($filename) {
            $file = $this->project->getAbsoluteFile($filename);
            unset($this->files[$file->getRelativePath()]);
        });
    }

    /**
     * @return string[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param $directory
     * @throws IllegalArgumentException
     */
    public function addDirectory($directory)
    {
        $file = $this->project->getAbsoluteFile($directory);

        if (!$file->isInRootDir()) {
            throw new IllegalArgumentException("Unable to add directory that is not in root directory of the project");
        }

        FileUtils::scan($directory, function ($file) {
            if (File::of($file)->isFile()) {
                $this->addFile($file);
            }
        });
    }

    /**
     * @param string  $zipFile
     */
    public function save($zipFile)
    {
        fs::ensureParent($zipFile);

        $zip = ZipFile::create($zipFile);

        foreach ($this->files as $name => $file) {
            $file = File::of($file);

            if ($file->exists()) {
                $zip->add($name, $file);
            }
        }
    }
}