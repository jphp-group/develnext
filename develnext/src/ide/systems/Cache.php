<?php
namespace ide\systems;

use ide\ui\LazyImage;
use ide\utils\FileUtils;
use php\gui\UXApplication;
use php\gui\UXImage;
use php\gui\UXImageView;
use php\io\File;
use php\lib\str;
use php\time\Time;

class Cache
{
    protected static $cacheImage = [];
    protected static $cacheImageView = [];

    public static function clear()
    {
        self::$cacheImageView = [];
        self::$cacheImage = [];
    }

    /**
     * @param $path
     * @param array|null $size
     * @return UXImageView
     */
    public static function getResourceImageView($path, array $size = null)
    {
        $key = $path;

        if ($size) {
            $key .= '_' . str::join($size, '_');
        }

        if ($view = self::$cacheImageView[$key]) {
            return $view;
        }

        $image = self::getResourceImage($path);
        $view = new UXImageView();
        $view->image = $image;

        if ($size) {
            $view->size = $size;
            $view->preserveRatio = true;
        }

        self::$cacheImageView[$key] = $view;
        return $view;
    }

    /**
     * @param $path
     * @return LazyImage|UXImage
     */
    public static function getResourceImage($path)
    {
        if (!str::startsWith($path, 'res://')) {
            $path = "res://$path";
        }

        list($image, $time) = self::$cacheImage[$path];

        if ($image) {
            return $image;
        }

        if (!UXApplication::isUiThread()) {
            return new LazyImage($path);
        }

        $image = new UXImage($path);
        self::$cacheImage[$path] = [$image, Time::millis()];

        return $image;
    }

    /**
     * @param string $file
     * @return UXImage|LazyImage|null
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

            if (!UXApplication::isUiThread()) {
                return new LazyImage($file);
            }

            $image = new UXImage($file);
            self::$cacheImage[$hash] = [$image, $file->lastModified()];

            return $image;
        } catch (\Exception $e) {
            return null;
        }
    }
}