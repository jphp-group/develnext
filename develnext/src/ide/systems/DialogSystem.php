<?php
namespace ide\systems;

use php\gui\UXDirectoryChooser;
use php\gui\UXFileChooser;

/**
 * Class DialogSystem
 * @package ide\systems
 */
class DialogSystem
{
    const DIALOG_OPEN_PROJECT = 'open_project';
    const DIALOG_PROJECTS_DIRECTORY = 'projects_directory';
    const DIALOG_IMAGE = 'image';
    const DIALOG_ANY_FILE = 'any_file';

    /**
     * @var UXFileChooser[]|UXDirectoryChooser[]
     */
    protected static $dialogs = [];

    static function get($code)
    {
        return static::$dialogs[$code];
    }

    static function getAnyFile()
    {
        return static::get(static::DIALOG_ANY_FILE);
    }

    static function getOpenProject()
    {
        return static::get(static::DIALOG_OPEN_PROJECT);
    }

    static function getProjectsDirectory()
    {
        return static::get(static::DIALOG_PROJECTS_DIRECTORY);
    }

    static function getImage()
    {
        return static::get(static::DIALOG_IMAGE);
    }

    static function fileDialog($code, $extensionFilter = [['description' => 'All files', 'extensions' => ['*.*']]])
    {
        static::$dialogs[$code] = $dialog = new UXFileChooser();
        $dialog->extensionFilters = $extensionFilter;

        return $dialog;
    }

    static function directoryDialog($code)
    {
        static::$dialogs[$code] = $dialog = new UXDirectoryChooser();
        return $dialog;
    }

    static function registerDefaults()
    {
        static::fileDialog(static::DIALOG_OPEN_PROJECT, [['description' => 'DevelNext проекты и архивы', 'extensions' => ['*.dnproject', '*.zip']]]);
        static::fileDialog(static::DIALOG_IMAGE, [['description' => 'Изображения (jpg, png, gif)', 'extensions' => ['*.jpg', '*.jpeg', '*.png', '*.gif']]]);
        static::fileDialog(static::DIALOG_ANY_FILE);
        static::directoryDialog(static::DIALOG_PROJECTS_DIRECTORY);
    }
}