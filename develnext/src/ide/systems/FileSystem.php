<?php
namespace ide\systems;

use ide\Ide;
use php\gui\UXTab;
use php\io\File;

class FileSystem
{
    /**
     * @var AbstractEditor[]
     */
    static protected $openedEditors = [];

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
        $info = static::$openedFiles[$path];

        if (!$info) {
            static::open($path, false);
            return;
        }

        if (File::of($path)->lastModified() > $info['time']) {
            // TODO: dialog to reload file?
        }
    }

    /**
     * @param $path
     * @param bool $switchToTab
     * @return AbstractEditor|null
     */
    static function open($path, $switchToTab = true)
    {
        $editor = static::$openedEditors[$path];
        $tab    = static::$openedTabs[$path];
        $info   = (array) static::$openedFiles[$path];

        if (!$editor) {
            $editor = Ide::get()->createEditor($path);
            $editor->load();

            $info['time'] = File::of($path)->lastModified();
        }

        if (!$tab) {
            $tab = new UXTab();
            $tab->text = $editor->getTitle();
            $tab->tooltip = $editor->getTooltip();
            $tab->style = '-fx-cursor: hand;';
            $tab->graphic = Ide::get()->getImage($editor->getIcon());
            $tab->content = $editor->makeUi();

            Ide::get()->getMainForm()->{'fileTabPane'}->tabs->add($tab);
        }

        if ($switchToTab) {
            Ide::get()->getMainForm()->{'fileTabPane'}->selectTab($tab);
        }

        static::$openedFiles[$path] = $info;

        return $editor;
    }

    static function close($path)
    {
        $editor = static::$openedTabs[$path];
        $tab    = static::$openedTabs[$path];

        if ($tab) {
            Ide::get()->getMainForm()->{'fileTabPane'}->tabs->remove($tab);
        }

        unset(static::$openedTabs[$path], static::$openedEditors[$path]);
    }
}