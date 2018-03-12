<?php
namespace ide;

use ide\project\Project;
use ide\project\ProjectFile;
use ide\utils\FileUtils;
use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lib\reflect;
use php\lib\str;
use php\util\Scanner;

trait VendorContainer
{
    function getVendorName()
    {
        return str::replace(reflect::typeOf($this), "\\", ".");
    }

    function getVendorDirectory()
    {
        return "vendor/" . $this->getVendorName();
    }

    function getVendorResource($name)
    {
        return "res://vendor/" . $this->getVendorName() . "/$name";
    }

    /**
     * @deprecated
     * @return bool
     */
    function copyVendorDirectory()
    {
        try {
            $stream = Stream::of($this->getVendorResource('.vendor'));

            $scanner = new Scanner($stream);

            while ($scanner->hasNextLine()) {
                $line = str::trim($scanner->nextLine());

                if ($line[0] == '#') continue;

                if ($line) {
                    $this->copyVendorResource($line);
                }
            }

            $stream->close();
            return true;
        } catch (IOException $e) {
            // skip.
            return false;
        }
    }

    function deleteVendorDirectory()
    {
        if (Ide::project()) {
            return FileUtils::deleteDirectory(Ide::project()->getFile($this->getVendorDirectory()));
        }

        return false;
    }

    function copyVendorResourceToFile($name, $file)
    {
        $vendorResource = $this->getVendorResource($name);
        FileUtils::copyFile($vendorResource, $file);
    }

    /**
     * Возвращает директорию вендора в проекте.
     *
     * @return ProjectFile|\php\io\File
     */
    function getProjectVendorDirectory()
    {
        return Ide::project()->getFile("vendor/{$this->getVendorName()}");
    }

    /**
     * @param string $path
     * @return File
     */
    function getProjectVendorFile($path)
    {
        return new File("{$this->getProjectVendorDirectory()}/$path");
    }

    function copyVendorResource($name)
    {
        if (Ide::project()) {
            $this->copyVendorResourceToFile($name, Ide::project()->getAbsoluteFile("{$this->getProjectVendorDirectory()}/$name"));
        } else {
            Logger::warn("Unable to copy vendor resource $name, project is not opened");
        }
    }

    function copyVendorResourceToProject($name, $projectPath = null, $toGenerated = false)
    {
        if (Ide::project()) {
            if ($projectPath === null) {
                $projectPath = $name;
            }

            $this->copyVendorResourceToFile($name, Ide::project()->getSrcFile($projectPath, $toGenerated));
        }
    }
}