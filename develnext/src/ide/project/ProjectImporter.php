<?php
namespace ide\project;

use ide\utils\FileUtils;
use php\compress\ArchiveInputStream;
use php\compress\ArchiveOutputStream;
use php\io\File;
use php\io\Stream;
use php\lib\Items;

class ProjectImporter
{
    protected $file;

    public function __construct($file)
    {
        $this->file = $file;
    }
    
    public function extract($projectDir)
    {
        $archive = new ArchiveInputStream('zip', $this->file);
        $entries = [];

        while ($entry = $archive->nextEntry()) {
            $entries[] = $entry;
        }

        $archive->close();

        $archive = new ArchiveInputStream('zip', $this->file);

        while ($entry = $archive->nextEntry()) {
            $entry = Items::shift($entries);

            $file = File::of("{$projectDir}/{$entry->getName()}");

            if ($entry->isDirectory()) {
                FileUtils::deleteDirectory($file);
                $file->mkdirs();
            } else {
                $file->delete();

                if ($parent = $file->getParentFile()) {
                    if (!$parent->isDirectory()) {
                        $parent->delete();
                        $parent->mkdirs();
                    }
                }

                $content = $archive->readFully();

                Stream::putContents($file, $content);
            }
        }

        $archive->close();
    }
}