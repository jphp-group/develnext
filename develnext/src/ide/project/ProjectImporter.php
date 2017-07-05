<?php
namespace ide\project;

use ide\utils\FileUtils;
use php\compress\ZipFile;

class ProjectImporter
{
    protected $file;

    public function __construct($file)
    {
        $this->file = $file;
    }
    
    public function extract($projectDir)
    {
        $zip = new ZipFile($this->file);

        foreach ($zip->statAll() as $stat) {
            if ($stat['directory']) {
                FileUtils::deleteDirectory("{$projectDir}/{$stat['name']}");
            }
        }

        $zip->unpack($projectDir);
    }
}