<?php
namespace ide\systems;

use ide\editors\AbstractEditor;
use ide\editors\form\IdeTabPane;
use ide\editors\menu\ContextMenu;
use ide\forms\MainForm;
use ide\Ide;
use ide\Logger;
use ide\utils\FileUtils;
use ide\utils\Json;
use ide\utils\UiUtils;
use php\gui\event\UXEvent;
use php\gui\event\UXMouseEvent;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXScrollPane;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXContextMenu;
use php\gui\UXDialog;
use php\gui\UXDndTabPane;
use php\gui\UXForm;
use php\gui\UXSplitPane;
use php\gui\UXTab;
use php\gui\UXMenu;
use php\gui\UXTabPane;
use php\io\File;
use php\lib\arr;
use php\lib\fs;
use php\lib\Items;
use timer\AccurateTimer;

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
     * @var callable
     */
    static protected $addTabClick;

    /**
     * @var UXTab[]
     */
    static protected $openedTabs = [];

    /**
     * @var array
     */
    static protected $openedFiles = [];

    /**
     * @var UXForm[]
     */
    static protected $openedWindows = [];

    /**
     * @var AbstractEditor
     */
    static protected $previousEditor = null;

    /**
     * @var bool
     */
    static protected $freeze = false;

    /**
     * @var array
     */
    static protected $editorContentDividePosition;

    /**
     * ..
     */
    static function clearCache()
    {
        static::$cachedEditors = [];
    }

    /**
     * @return UXForm[]
     */
    public static function getOpenedWindows()
    {
        return static::$openedWindows;
    }



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

            if (self::getSelectedEditor() !== $editor) {
                $tab = null;

                foreach (self::getOpenedTabs() as $tab) {
                    if ($tab->userData === $editor) {
                        static::$freeze = true;
                        Ide::get()->getMainForm()->{'fileTabPane'}->selectTab($tab);
                        static::$freeze = false;

                        break;
                    }
                }

                foreach (static::$openedWindows as $window) {
                    if ($window->userData === $editor) {
                        static::$freeze = true;
                        $window->toFront();
                        static::$freeze = false;
                        break;
                    }
                }

                static::_openEditor($editor, $param);
            } else {
                $editor->open($param);
            }

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
     * @return AbstractEditor[]
     */
    static function getOpenedEditors()
    {
        return static::$openedEditors;
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
     * @return UXTab[]
     */
    public static function getOpenedTabs()
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
     * @param string $path
     * @return bool
     */
    static function isOpenedAndSelected($path)
    {
        return self::isOpened($path) && FileUtils::equalNames(self::getSelected(), $path);
    }

    /**
     * @param string $path
     * @return bool
     */
    static function isTabbed($path)
    {
        $hash = FileUtils::hashName($path);

        return !isset(static::$openedWindows[$hash]) && static::isOpened($path);
    }

    /**
     * ...
     */
    static function saveAll()
    {
        foreach (static::$openedEditors as $editor) {
            if ($editor->isCorrectFormat()) {
                $editor->save();
            }
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

    /**
     * @param $path
     * @param bool $cache
     * @return AbstractEditor|null
     */
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
            if (!fs::exists($path)) {
                Logger::error("Unable fetch editor, path '$path' is not found");
            }

            return null;
        }

        $editor->load();

        static::$cachedEditors[$hash] = $editor;

        return $editor;
    }

    static public function _openEditor($editor, $param = null)
    {
        if ($editor instanceof AbstractEditor && $editor !== static::$previousEditor) {
            if (static::$previousEditor && static::$previousEditor->isTabbed()) {
                static::$previousEditor->hide();

                static::$previousEditor = $editor;
            }

            $editor->open($param);
        }

        $project = Ide::project();

        if ($project) {
            $project->update();
        }
    }

    static private $editorSplitDividerWidth = 250;

    static private function makeUiForEditor(AbstractEditor $editor, $type = 'tab')
    {
        $content = $editor->makeUi();

        if ($leftPaneUi = $editor->makeLeftPaneUi()) {
            $editor->setLeftPaneUi($leftPaneUi);

            if ($leftPaneUi instanceof IdeTabPane) {
                $leftPaneUi = $leftPaneUi->makeUi();
            }

            $wrapScroll = new UXScrollPane($leftPaneUi);
            $wrapScroll->fitToHeight = true;
            $wrapScroll->fitToWidth = true;
            UXAnchorPane::setAnchor($wrapScroll, 0);

            $wrap = new UXAnchorPane();
            $wrap->width = static::$editorSplitDividerWidth;
            $wrap->add($wrapScroll);
            UXSplitPane::setResizeWithParent($wrap, false);

            $content = new UXSplitPane([$wrap, $content]);

            if ($type == 'tab') {
                $wrap->observer('width')->addListener(function ($_, $value) {
                    if ($value > 50) {
                        static::$editorSplitDividerWidth = $value;
                    }
                });

                $content->observer('width')->addOnceListener(function ($_, $width) use ($wrap, $content) {
                    $content->dividerPositions = [$wrap->width / $width];
                });
            } else {
                $content->dividerPositions = [0.2];
            }
        } else {
            $editor->setLeftPaneUi(null);
        }

        return $content;
    }

    static private function makeWindowForEditor(AbstractEditor $editor)
    {
        $editor->setTabbed(false);

        $win = new UXForm();

        $win->addStylesheet('/php/gui/framework/style.css');
        foreach (app()->getStyles() as $one) {
            $win->addStylesheet($one);
        }

        $win->owner = Ide::get()->getMainForm();
        $win->title = $editor->getTitle() . " [" . $editor->getFile() . "]";

        if ($editor->getIcon()) {
            $win->icons->add(Ide::get()->getImage($editor->getIcon())->image);
        } else {
            $win->icons->addAll(Ide::get()->getMainForm()->icons);
        }

        $win->layout = static::makeUiForEditor($editor, 'window');
        $win->layout->size = [Ide::get()->getMainForm()->width * 0.6, Ide::get()->getMainForm()->height * 0.7];

        $changeHandler = function (UXEvent $e = null, $param = null) use ($editor) {
            Logger::debug("Opening window editor '{$editor->getTitle()}'");
            self::_openEditor($editor, $param);
        };

        $win->observer('focused')->addListener(function ($_, $new) use ($editor, $changeHandler) {
            if (!$new) {
                if (self::isOpened($editor->getFile())) {
                    Logger::debug("Leave window '{$editor->getTitle()}'");
                    $editor->leave();
                }
            } else {
                $changeHandler();
            }
        });

        $win->on('close', function () use ($editor) {
            if (!static::$freeze) {
                static::close($editor->getFile(), false);
            }
        });

        //$win->on('showing', $changeHandler);
        $win->data('change-handler', $changeHandler);

        return $win;
    }

    static private function makeTabForEditor(AbstractEditor $editor)
    {
        $tab = new UXTab();

        $editor->setTabbed(true);

        $tab->text = $editor->getTitle();
        $tab->tooltip = $editor->getTooltip();
        $tab->style = UiUtils::fontSizeStyle() . "; " . $editor->getTabStyle();
        $tab->graphic = Ide::get()->getImage($editor->getIcon());
        $tab->content = static::makeUiForEditor($editor, 'tab');
        $tab->userData = $editor;

        $tab->closable = $editor->isCloseable();

        /** @var MainForm $mainForm */
        $mainForm = Ide::get()->getMainForm();

        $tab->on('closeRequest', function (UXEvent $e) use ($editor) {
            static::close($editor->getFile(), false);
        });

        $changeHandler = function (UXEvent $e = null, $param = null) use ($mainForm, $editor) {
            /** @var UXTabPane $fileTabPane */
            $fileTabPane = Ide::get()->getMainForm()->{'fileTabPane'};

            if ($e && $e->sender === $fileTabPane->selectedTab) {
                return;
            }

            if (static::$freeze) {
                return;
            }

            if ($e && $e->sender instanceof UXTab && $e->sender->userData instanceof AbstractEditor) {
                $editor = $e->sender->userData;

                uiLater(function () use ($editor) {
                    if (static::isOpened($editor->getFile())) {
                        Logger::debug("Leave tab '{$editor->getTitle()}'");
                        $editor->leave();
                    }
                });
            }

            uiLater(function () use ($mainForm, $editor, $fileTabPane, $param) {
                $tab = $fileTabPane->selectedTab;

                if ($tab) {
                    Logger::debug("Opening selected tab '$tab->text'");

                    if (static::$editorSplitDividerWidth && $tab->content instanceof UXSplitPane) {
                        $tab->content->dividerPositions = [(static::$editorSplitDividerWidth + 3) / $tab->content->layoutBounds['width']];
                    }

                    static::_openEditor($tab->userData, $param);
                }
            });
        };

        $tab->data('change-handler', $changeHandler);

        uiLater(function () use ($tab, $changeHandler) {
            $tab->on('change', $changeHandler);
        });

        static::addTab($tab, $editor->isPrependTab());

        UXDndTabPane::setDraggable($tab, $editor->isDraggable());

        return $tab;
    }

    static function switchToWindow($path)
    {
        if ($win = static::$openedWindows[FileUtils::hashName($path)]) {
            $win->toFront();
            return;
        }

        if ($editor = static::getOpenedEditor($path)) {
            static::close($path);

            static::open($path, true, null, true);
        }
    }

    /**
     * @param $path
     * @param bool $switchToEditor
     * @param null $param
     * @param bool $inWindow
     * @return AbstractEditor|null
     */
    static function open($path, $switchToEditor = true, $param = null, $inWindow = false)
    {
        if ($path instanceof AbstractEditor) {
            $path = $path->getFile();
        }

        if (Ide::project() && fs::exists($path)) {
            $path = Ide::project()->getAbsoluteFile($path);
        }

        $hash = FileUtils::hashName($path);

        $editor = static::$openedEditors[$hash];
        $tab    = static::$openedTabs[$hash];
        $win    = static::$openedWindows[$hash];
        $info   = (array) static::$openedFiles[$hash];

        if (!$editor) {
            $editor = self::fetchEditor($path);

            if (!$editor) {
                return null;
            }

            $info['file'] = $path;
            $info['mtime'] = File::of($path)->lastModified();
        }

        if ($editor->isIncorrectFormat()) {
            Ide::get()->getMainForm()->toast("Ошибка загрузки данных, некорректный или поврежденный файл.\n\n{$path}");
            return null;
        }

        if ($inWindow) {
            if (!$win) {
                $win = FileSystem::makeWindowForEditor($editor);
                $changeHandler = $win->data('change-handler');

                if ($switchToEditor && is_callable($changeHandler)) {
                    $changeHandler(null, $param);
                }
            }

            if ($switchToEditor) {
                $win->show();
                $win->toFront();
            }
        } else {
            if (!$tab) {
                $tab = static::makeTabForEditor($editor);
                $changeHandler = $tab->data('change-handler');

                if ($switchToEditor && is_callable($changeHandler)) {
                    $changeHandler(null, $param);
                }
            } else {
                if (!self::isOpenedAndSelected($path)) {
                    if (static::$editorSplitDividerWidth && $tab->content instanceof UXSplitPane) {
                        uiLater(function () use ($tab) {
                            $tab->content->dividerPositions = [static::$editorSplitDividerWidth / $tab->content->width];
                        });
                    }
                }
            }

            if ($switchToEditor) {
                Ide::get()->getMainForm()->{'fileTabPane'}->selectTab($tab);
            }
        }


        static::$openedFiles[$hash] = $info;
        static::$openedEditors[$hash] = $editor;

        if ($inWindow) {
            static::$openedWindows[$hash] = $win;
        } else {
            static::$openedTabs[$hash] = $tab;
        }

        return $editor;
    }

    static function closeAllTabs()
    {
        Ide::get()->getMainForm()->{'fileTabPane'}->tabs->clear();
    }

    static function close($path, $removeUiEditor = true, $save = true)
    {
        Logger::debug("Close file '$path', removeUiEditor = $removeUiEditor, save = $save");

        $hash = FileUtils::hashName($path);

        /** @var AbstractEditor $editor */
        $editor = static::$openedEditors[$hash];
        $tab    = static::$openedTabs[$hash];
        $win    = static::$openedWindows[$hash];

        unset(
            static::$openedTabs[$hash],
            static::$openedEditors[$hash],
            static::$openedFiles[$hash],
            static::$cachedEditors[$hash],
            static::$openedWindows[$hash]
        );

        if ($editor) {
            $editor->close($editor->isCorrectFormat() && $save);
        }

        if ($removeUiEditor) {
            if ($tab) {
                Ide::get()->getMainForm()->{'fileTabPane'}->tabs->remove($tab);
            }

            if ($win) {
                static::$freeze = true;
                $win->hide();
                static::$freeze = false;
            }
        }
    }

    static function setClickOnAddTab(callable $callback = null)
    {
        static::$addTabClick = $callback;

        if (static::$addTab) {
            if ($callback) {
                self::showAddTab();
            } else {
                self::hideAddTab();
            }
        }
    }

    private static function addTab(UXTab $tab, $prepend = false)
    {
        /** @var UXTabPane $fileTabPane */
        $fileTabPane = Ide::get()->getMainForm()->{'fileTabPane'};

        if ($prepend) {
            $fileTabPane->tabs->insert(0, $tab);
        } else {
            static::hideAddTab();
            $fileTabPane->tabs->add($tab);
            static::showAddTab();
        }
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
            UXDndTabPane::setDraggable($tab, false);
            $tab->closable = false;
            $tab->style = '-fx-cursor: hand; -fx-padding: 1px 0px;';
            $tab->graphic = Ide::get()->getImage('icons/plus16.png');

            $button = new UXButton();
            $tab->graphic = $button;

            $button->graphic = Ide::get()->getImage('icons/plus16.png');
            $button->classes->add('dn-add-tab-button');
            $button->style = '-fx-background-radius: 0; -fx-border-radius: 0; -fx-border-width: 0';

            $button->on('click', function ($e) {
                call_user_func(static::$addTabClick, $e);
            });

            static::$addTab = $tab;
        }

        if (static::$addTabClick) {
            $fileTabPane->tabs->add(static::$addTab);
            UXDndTabPane::setDraggable(static::$addTab, false);
        }
    }

    /**
     * Open next tab (it's for Ctrl + Tab)
     */
    public static function openNext()
    {
        Logger::info("Open next...");

        /** @var UXTabPane $fileTabPane */
        $fileTabPane = Ide::get()->getMainForm()->{'fileTabPane'};

        $index = $fileTabPane->selectedIndex;

        if ($index >= $fileTabPane->tabs->count-1 || (static::$addTab && $index >= $fileTabPane->tabs->count-2)) {
            $index = 0;
        } else {
            $index += 1;
        }

        $nextTab = $fileTabPane->tabs[$index];

        if ($nextTab && $nextTab->userData instanceof AbstractEditor) {
            static::open($nextTab->userData->getFile());
        } else {
            Logger::warn("Unable to open next tab, index = $index, tab = $nextTab");
        }
    }
}