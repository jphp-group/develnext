<?php
namespace ide;

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
}