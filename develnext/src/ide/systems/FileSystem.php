<?php
namespace ide\systems;

use ide\editors\AbstractEditor;
use ide\editors\menu\ContextMenu;
use ide\forms\MainForm;
use ide\Ide;
use ide\Logger;
use ide\utils\FileUtils;
use ide\utils\Json;
use php\gui\event\UXEvent;
use php\gui\event\UXMouseEvent;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXContextMenu;
use php\gui\UXDialog;
use php\gui\UXDraggableTab;
use php\gui\UXMenu;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\io\File;
use php\lib\Items;

class FileSystem
{
    /**
     * @var AbstractEditor[]
     */
    static protected $openedEditors = [];

    /**
     * @var AbstractEditor[]
     */
    static protected $cachedEditors = [];

    /**
     * @var UXTab
     */
    static protected $addTab;

    /**
     * @var UXDraggableTab[]
     */
    static protected $openedTabs = [];

    /**
     * @var array
     */
    static protected $openedFiles = [];

    /**
     * @var AbstractEditor
     */
    static protected $previousEditor = null;

    /**
     * @param $path
     * @param null $param
     */
    static function refresh($path, $param = null)
    {
        $hash = FileUtils::hashName($path);
        $info = static::$openedFiles[$hash];

        if (!$info) {
            static::open($path, false, $param);
            return;
        }
    }

    /**
     * @param $path
     * @param null $param
     * @return AbstractEditor|null
     */
    static function openOrRefresh($path, $param = null)
    {
        $hash = FileUtils::hashName($path);
        $info = static::$openedFiles[$hash];

        if (!$info) {
            return static::open($path, true, $param);
        } else {
            $editor = static::getOpenedEditor($path);
            $editor->open($param);
            return $editor;
        }
    }

    /**
     * @return array
     */
    static function getOpened()
    {
        $result = [];

        foreach (static::getOpenedTabs() as $tab) {
            if ($tab->userData instanceof AbstractEditor) {
                $file = "{$tab->userData->getFile()}";

                $result[FileUtils::hashName($file)] = [
                    'file' => $file,
                    'mtime' => File::of($file)->lastModified(),
                ];
            }
        }

        return $result;
    }

    /**
     * @param $file
     * @return AbstractEditor
     */
    static function getOpenedEditor($file)
    {
        return static::$openedEditors[FileUtils::hashName($file)];
    }

    /**
     * @return UXDraggableTab[]
     */
    private static function getOpenedTabs()
    {
        /** @var UXTabPane $fileTabPane */
        $fileTabPane = Ide::get()->getMainForm()->{'fileTabPane'};

        return items::toArray($fileTabPane->tabs);
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
     * @return null|string
     */
    static function getSelected()
    {
        $editor = static::getSelectedEditor();

        if ($editor) {
            return $editor->getFile();
        }

        return null;
    }

    /**
     * @return AbstractEditor|null
     */
    static function getSelectedEditor()
    {
        /** @var UXTabPane $fileTabPane */
        $mainForm = Ide::get()->getMainForm();

        if (!$mainForm) {
            return null;
        }

        $fileTabPane = $mainForm->{'fileTabPane'};

        if (!$fileTabPane) {
            return null;
        }

        $tab = $fileTabPane->selectedTab;

        if ($tab && $tab->userData instanceof AbstractEditor) {
            return $tab->userData;
        }

        return null;
    }

    static function fetchEditor($path, $cache = false)
    {
        $hash = FileUtils::hashName($path);

        if ($editor = static::$openedEditors[$hash]) {
            return $editor;
        }

        if ($cache && ($editor = static::$cachedEditors[$hash])) {
            return $editor;
        }

        $editor = Ide::get()->createEditor($path);

        if (!$editor) {
            return null;
        }

        $editor->load();

        static::$cachedEditors[$hash] = $editor;

        return $editor;
    }

    /**
     * @param $path
     * @param bool $switchToTab
     * @param null $param
     * @return AbstractEditor|null
     * @throws \php\lang\IllegalStateException
     */
    static function open($path, $switchToTab = true, $param = null)
    {
        $hash = FileUtils::hashName($path);

        $editor = static::$openedEditors[$hash];
        $tab    = static::$openedTabs[$hash];
        $info   = (array) static::$openedFiles[$hash];

        if (!$editor) {
            $editor = self::fetchEditor($path);

            if (!$editor) {
                return null;
            }

            $info['file'] = $path;
            $info['mtime'] = File::of($path)->lastModified();
        }

        if (!$tab) {
            $tab = new UXDraggableTab();
            $tab->detachable = false;
            $tab->disableDragFirst = true;
            $tab->disableDragLast = true;
            $tab->draggable = $editor->isDraggable();

            $tab->text = $editor->getTitle();
            $tab->tooltip = $editor->getTooltip();
            $tab->style = '-fx-cursor: hand; ' . $editor->getTabStyle();
            $tab->graphic = Ide::get()->getImage($editor->getIcon());
            $tab->content = $editor->makeUi();
            $tab->userData = $editor;

            $tab->closable = $editor->isCloseable();

            /** @var MainForm $mainForm */
            $mainForm = Ide::get()->getMainForm();

            $leftPaneUi = $editor->makeLeftPaneUi();
            $editor->setLeftPaneUi($leftPaneUi);

            $mainForm->clearLeftPane();

            if ($leftPaneUi) {
                $mainForm->setLeftPane($leftPaneUi);
            }

            $tab->on('closeRequest', function (UXEvent $e) use ($path, $editor) {
                static::close($path, false);
            });

            $changeHandler = function (UXEvent $e = null, $param = null) use ($mainForm, $path) {
                /** @var UXTabPane $fileTabPane */
                $fileTabPane = Ide::get()->getMainForm()->{'fileTabPane'};

                if ($e && $e->sender === $fileTabPane->selectedTab) {
                    return;
                }

                uiLater(function () use ($mainForm, $path, $fileTabPane, $param) {
                    $tab = $fileTabPane->selectedTab;

                    Logger::info("Opening selected tab '$tab->text'");

                    if ($tab->userData instanceof AbstractEditor && $tab->userData !== static::$previousEditor) {
                        if (static::$previousEditor) {
                            static::$previousEditor->hide();
                        }

                        $mainForm->clearLeftPane();

                        if ($tab->userData->getLeftPaneUi()) {
                            $mainForm->setLeftPane($tab->userData->getLeftPaneUi());
                        }

                        $tab->userData->open($param);

                        static::$previousEditor = $tab->userData;
                    } else {
                        $previousEditor = null;
                    }

                    $project = Ide::project();

                    if ($project) {
                        $project->update();
                    }
                });
            };

            uiLater(function () use ($tab, $changeHandler) {
                $tab->on('change', $changeHandler);
            });

            if ($switchToTab) {
                $changeHandler(null, $param);
            }

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

    static function close($path, $removeTab = true)
    {
        $hash = FileUtils::hashName($path);

        /** @var AbstractEditor $editor */
        $editor = static::$openedEditors[$hash];
        $tab    = static::$openedTabs[$hash];

        unset(static::$openedTabs[$hash], static::$openedEditors[$hash], static::$openedFiles[$hash], static::$cachedEditors[$hash]);

        if ($editor) {
            $editor->close();
        }

        if ($removeTab && $tab) {
            Ide::get()->getMainForm()->{'fileTabPane'}->tabs->remove($tab);
        }
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
            $tab = new UXDraggableTab();
            $tab->detachable = false;
            $tab->closable = false;

            $button = new UXButton();
            $tab->graphic = $button;

            $button->graphic = Ide::get()->getImage('icons/plus16.png');
            $tab->style = '-fx-cursor: hand; -fx-padding: 1px 0px;';

            $button->on('click', function (UXMouseEvent $e) {
                $contextMenu = new UXContextMenu();

                /** @var UXMenu $menu */
                $menu = Ide::get()->getMainForm()->{'menuCreate'};

                foreach ($menu->items as $item) {
                    $contextMenu->items->add($item);
                }

                $contextMenu->show(Ide::get()->getMainForm(), $e->screenX, $e->screenY);
            });

            static::$addTab = $tab;
        }

        $fileTabPane->tabs->add(static::$addTab);
    }
}