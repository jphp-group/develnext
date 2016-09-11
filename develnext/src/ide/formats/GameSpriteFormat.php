<?php
namespace ide\formats;

use Files;
use ide\editors\AbstractEditor;
use ide\editors\GameSpriteEditor;
use ide\Logger;
use ide\utils\FileUtils;
use php\lib\Str;

class GameSpriteFormat extends AbstractFormat
{
    /**
     * @return string
     */
    public function getIcon()
    {
        return 'icons/picture16.png';
    }

    /**
     * @param $path
     * @return string
     */
    public function getTitle($path)
    {
        return FileUtils::stripExtension(parent::getTitle($path));
    }

    /**
     * @param $file
     * @return AbstractEditor
     */
    public function createEditor($file)
    {
        return new GameSpriteEditor($file);
    }

    /**
     * @param $file
     * @return bool
     */
    public function isValid($file)
    {
        return Files::isFile($file) && Str::endsWith($file, ".sprite");
    }

    public function delete($path)
    {
        parent::delete($path);

        $name = FileUtils::stripExtension($path);

        if (!Files::delete($name . ".png")) {
            Logger::warn("Cannot delete file '$name.png'");
        }

        Files::delete($name . ".jpeg");
        Files::delete($name . ".jpg");
        Files::delete($name . ".gif");

        if (Files::isDir($name)) {
            FileUtils::deleteDirectory($name);
        }
    }


    /**
     * @param $any
     * @return mixed
     */
    public function register($any)
    {

    }
}