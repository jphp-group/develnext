<?php
namespace ide\systems;

use ide\utils\FileUtils;
use php\gui\UXImage;
use php\io\File;

class Cache
{
    protected static $cacheImage = [];

    /**
     * @param string $file
     * @return UXImage|null
     */
    public static function getImage($file)
    {
        $file = new File($file);
        if (!$file->exists()) {
            return null;
        }

        try {
            $hash = FileUtils::hashName($file);

            list($image, $time) = self::$cacheImage[$hash];

            if ($image && $time && $time == $file->lastModified()) {
                return $image;
            }

            $image = new UXImage($file);
            self::$cacheImage[$hash] = [$image, $file->lastModified()];

            return $image;
        } catch (\Exception $e) {
            return null;
        }
    }
}