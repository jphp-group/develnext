<?php
namespace ide\systems;

use ide\editors\AbstractEditor;
use ide\Ide;
use ide\utils\FileUtils;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\io\File;

class FileSystem
{
    /**
     * @var AbstractEditor[]
     */
    static protected $openedEditors = [];

    /**
     * @var UXTab
     */
    static protected $addTab;

    /**
     * @var UXTab[]
     */
    static protected $openedTabs = [];

    /**
     * @var array
     */
    static protected $openedFiles = [];

    /**
     * @param $path
     */
    static function refresh($path)
    {
        $hash = FileUtils::hashName($path);
        $info = static::$openedFiles[$hash];

        if (!$info) {
            static::open($path, false);
            return;
        }
    }

    /**
     * @return array
     */
    static function getOpened()
    {
        return static::$openedFiles;
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    static function isOpened($path)
    {
        $hash = FileUtils::hashName($path);

        return isset(static::$openedFiles[$hash]);
    }

    /**
     * ...
     */
    static function saveAll()
    {
        foreach (static::$openedEditors as $editor) {
            $editor->save();
        }
    }

    /**
     * @param $path
     * @param bool $switchToTab
     * @return AbstractEditor|null
     */
    static function open($path, $switchToTab = true)
    {
        $hash = FileUtils::hashName($path);

        $editor = static::$openedEditors[$hash];
        $tab    = static::$openedTabs[$hash];
        $info   = (array) static::$openedFiles[$hash];

        if (!$editor) {
            $editor = Ide::get()->createEditor($path);

            if (!$editor) {
                return null;
            }

            $editor->load();

            $info['file'] = $path;
            $info['mtime'] = File::of($path)->lastModified();
        }

        if (!$tab) {
            $tab = new UXTab();
            $tab->text = $editor->getTitle();
            $tab->tooltip = $editor->getTooltip();
            $tab->style = '-fx-cursor: hand;';
            $tab->graphic = Ide::get()->getImage($editor->getIcon());
            $tab->content = $editor->makeUi();

            $tab->on('close', function () use ($path, $editor) {
                static::close($path);
            });

            static::addTab($tab);
        }

        if ($switchToTab) {
            Ide::get()->getMainForm()->{'fileTabPane'}->selectTab($tab);
        }

        static::$openedFiles[$hash] = $info;
        static::$openedTabs[$hash] = $tab;
        static::$openedEditors[$hash] = $editor;

        return $editor;
    }

    static function close($path)
    {
        $hash = FileUtils::hashName($path);

        /** @var AbstractEditor $editor */
        $editor = static::$openedEditors[$hash];
        $tab    = static::$openedTabs[$hash];

        if ($editor) {
            $editor->close();
        }

        if ($tab) {
            Ide::get()->getMainForm()->{'fileTabPane'}->tabs->remove($tab);
        }

        unset(static::$openedTabs[$hash], static::$openedEditors[$hash], static::$openedFiles[$hash]);
    }

    private static function addTab(UXTab $tab)
    {
        /** @var UXTabPane $fileTabPane */
        $fileTabPane = Ide::get()->getMainForm()->{'fileTabPane'};

        static::hideAddTab();
        $fileTabPane->tabs->add($tab);
        static::showAddTab();
    }

    private static function hideAddTab()
    {
        if (static::$addTab) {
            /** @var UXTabPane $fileTabPane */
            $fileTabPane = Ide::get()->getMainForm()->{'fileTabPane'};

            $fileTabPane->tabs->remove(static::$addTab);
        }
    }

    private static function showAddTab()
    {
        /** @var UXTabPane $fileTabPane */
        $fileTabPane = Ide::get()->getMainForm()->{'fileTabPane'};

        if (!static::$addTab) {
            $tab = new UXTab();
            $tab->closable = false;
            $tab->graphic = Ide::get()->getImage('icons/plus16.png');
            $tab->style = '-fx-cursor: hand; -fx-padding: 1px 7px;';

            static::$addTab = $tab;
        }

        $fileTabPane->tabs->add(static::$addTab);
    }
}