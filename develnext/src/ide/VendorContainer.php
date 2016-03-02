<?php
namespace ide;

use ide\project\Project;
use ide\utils\FileUtils;
use php\lib\str;

trait VendorContainer
{
    function getVendorResource($name)
    {
        return "res://.data/vendor/" . str::replace(get_class($this), "\\", ".") . "/$name";
    }

    function copyVendorResourceToFile($name, $file)
    {
        $vendorResource = $this->getVendorResource($name);
        FileUtils::copyFile($vendorResource, $file);
    }

    function copyVendorResourceToProject($name, $projectPath = null)
    {
        if (Ide::project()) {
            if ($projectPath === null) {
                $projectPath = $name;
            }

            $this->copyVendorResourceToFile($name, Ide::project()->getSrcFile($projectPath));
        }
    }
}