<?php
namespace ide\project;
use ide\utils\FileUtils;
use php\compress\ArchiveOutputStream;
use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lang\IllegalArgumentException;
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
        $file = $this->project->getAbsoluteFile($file);
        unset($this->files[$file->getRelativePath()]);
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
     * @param $zipFile
     */
    public function save($zipFile)
    {
        if ($parent = File::of($zipFile)->getParentFile()) {
            $parent->mkdirs();
        }

        $out = new ArchiveOutputStream('zip', $zipFile);

        foreach ($this->files as $name => $file) {
            $file = File::of($file);

            if ($file->exists()) {
                $contents = Stream::getContents($file);

                $entity = $out->createEntry($file, $name);
                $entity->setSize(Str::length($contents));

                $out->addEntry($entity);
                $out->write($contents);

                $out->closeEntry();
            }
        }

        $out->close();
    }
}