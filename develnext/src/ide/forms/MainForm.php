<?php
namespace ide\forms;

use ide\editors\form\IdeTabPane;
use ide\forms\mixins\SavableFormMixin;
use ide\Ide;
use ide\IdeException;
use ide\Logger;
use ide\project\templates\DefaultGuiProjectTemplate;
use ide\systems\FileSystem;
use ide\systems\ProjectSystem;
use ide\systems\WatcherSystem;
use ide\utils\FileUtils;
use php\desktop\HotKeyManager;
use php\desktop\Robot;
use php\gui\designer\UXDesigner;
use php\gui\designer\UXDirectoryTreeValue;
use php\gui\designer\UXDirectoryTreeView;
use php\gui\designer\UXFileDirectoryTreeSource;
use php\gui\dock\UXDockNode;
use php\gui\dock\UXDockPane;
use php\gui\event\UXEvent;
use php\gui\event\UXKeyboardManager;
use php\gui\event\UXKeyEvent;
use php\gui\event\UXMouseEvent;
use php\gui\framework\AbstractForm;
use php\gui\framework\Preloader;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXAlert;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXForm;
use php\gui\UXImage;
use php\gui\UXImageView;
use php\gui\UXMenu;
use php\gui\UXMenuBar;
use php\gui\UXNode;
use php\gui\UXScreen;
use php\gui\UXSplitPane;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\gui\UXTextArea;
use php\gui\UXTreeView;
use php\io\File;
use php\lang\System;
use php\lib\fs;
use php\lib\str;
use script\TimerScript;

/**
 * @property UXTabPane $fileTabPane
 * @property UXTabPane $projectTabs
 * @property UXVBox $properties
 * @property UXAnchorPane $directoryTree
 * @property UXTreeView $projectTree
 * @property UXHBox $headPane
 * @property UXHBox $headRightPane
 * @property UXSplitPane $contentSplitPane
 * @property UXVBox $contentVBox
 * @property UXAnchorPane $bottomSpoiler
 * @property UXTabPane $bottomSpoilerTabPane
 *
 * @property UXDockPane $dockPane
 */
class MainForm extends AbstractIdeForm
{
    /**
     * @var UXMenuBar
     */
    public $mainMenu;


    private $contentSplitPaneSm;

    /**
     * MainForm constructor.
     */
    public function __construct()
    {
        parent::__construct();

        foreach ($this->contentVBox->children as $one) {
            if ($one instanceof UXMenuBar) {
                $this->mainMenu = $one;
                break;
            }
        }

        if (!$this->mainMenu) {
            throw new IdeException("Cannot find main menu on main form");
        }

        $this->contentSplitPaneSm = $this->contentSplitPane->items[0];
    }

    /**
     * @param $string
     * @return null|UXMenu
     */
    public function findSubMenu($string)
    {
        /** @var UXMenu $one */
        foreach ($this->mainMenu->menus as $one) {
            if ($one->id == $string) {
                return $one;
            }
        }

        return null;
    }

    protected function init()
    {
        parent::init();

        $this->opacity = 0.01;

        Ide::get()->on('start', function () {
            $this->opacity = 1;
        });

        $mainMenu = $this->mainMenu; // FIX!!!!! see FixSkinMenu

        $this->headRightPane->spacing = 5;
       // $this->contentSplit->items->removeByIndex(1); // TODO implement bottom slider.

        $pane = UXTabPane::createDefaultDnDPane();

        $parent = $this->fileTabPane->parent;
        $this->fileTabPane->free();

        /** @var UXTabPane $tabPane */
        $tabPane = $pane ? $pane->children[0] : new UXTabPane();
        $tabPane->id = 'fileTabPane';
        $tabPane->tabClosingPolicy = 'ALL_TABS';

        // todo fix bug
        /*$tabPane->on('keyDown', $keyDown = function (UXKeyEvent $e) {
            if ($e->controlDown && $e->codeName == 'Tab') {
                $e->consume();
                FileSystem::openNext();
            }
        });*/

        if ($pane) {
            UXAnchorPane::setAnchor($pane, 0);
            $parent->add($pane);

            // fix bug
            // $pane->on('keyDown', $keyDown);
        } else {
            $parent->add($tabPane);
        }

        /*$properties = new UXDockNode($this->properties, 'Инспектор');
        $properties->size = [200, 700];
        $properties->dock($this->dockPane, 'LEFT');

        TimerScript::executeAfter(1000, function () use ($properties) {
            $this->properties->free();
            $properties->add($this->properties);
        });

        $center = new UXDockNode(new UXAnchorPane(), 'Редактор');
        $center->size = [9000, 800];
        $center->dock($this->dockPane, 'RIGHT');
        $center->floatable = false;
        $center->minimizable = false;*/


        /*$hotkeyManager = new HotKeyManager();
        $hotkeyManager->register('control shift PLUS', function () {
            System::halt(1);
        }); */

        $tree = new UXDirectoryTreeView();
        $tree->position = [0, 0];
        $this->directoryTree->add($tree);

        UXAnchorPane::setAnchor($tree, 0);

        Ide::get()->bind('openProject', function () use ($tree) {
            $project = Ide::project();
            $project->getTree()->setView($tree);

            $tree->treeSource = $project->getTree()->createSource();

            $tree->root->expanded = true;
            $project->getConfig()->loadTreeState($project->getTree());
        });

        Ide::get()->bind('afterCloseProject', function () use ($tree) {
            $tree->treeSource->shutdown();
            $tree->treeSource = null;
        });
    }

    /**
     * @param string $id
     * @param string $text
     * @param bool $prepend
     * @throws IdeException
     */
    public function defineMenuGroup($id, $text, $prepend = false)
    {
        $id = str::upperFirst($id);

        $menu = $this->{"menu$id"};

        if ($menu == null) {
            $menu = new UXMenu();
            $menu->id = "menu$id";

            if ($prepend) {
                $this->mainMenu->menus->insert(0, $menu);
            } else {
                $this->mainMenu->menus->add($menu);
            }
        } else {
            if (!($menu instanceof UXMenu)) {
                throw new IdeException("Invalid menu class for id = 'menu$id'");
            }
        }

        $menu->text = $text;
    }

    private $contentSplitPaneDividerPositions;

    /**
     * @param IdeTabPane|UXNode $pane
     */
    public function setLeftPane($pane)
    {
        if ($pane) {
            if ($this->contentSplitPane->items[0] !== $this->contentSplitPaneSm) {
                $this->contentSplitPane->items->insert(0, $this->contentSplitPaneSm);

                $this->contentSplitPane->dividerPositions = $this->contentSplitPaneDividerPositions;
            }

            $this->clearLeftPane();
        } else {
            if ($this->contentSplitPane->items[0] === $this->contentSplitPaneSm) {
                $this->contentSplitPaneDividerPositions = $this->contentSplitPane->dividerPositions;
                $this->contentSplitPane->items->removeByIndex(0);
            }
        }

        if ($pane instanceof IdeTabPane) {
            $this->properties->children->add($pane->makeUi());
        } else {
            if ($pane) {
                $this->properties->children->add($pane);
            }
        }
    }

    public function clearLeftPane()
    {
        if ($this->properties) {
            $this->properties->children->clear();
        }
    }

    public function show()
    {
        parent::show();
        Logger::info("Show main form ...");

        $screen = UXScreen::getPrimary();

        $this->contentSplitPane->dividerPositions = Ide::get()->getUserConfigArrayValue(get_class($this) . '.dividerPositions', $this->contentSplitPane->dividerPositions);
        $this->contentSplitPaneDividerPositions = $this->contentSplitPane->dividerPositions;

        $this->width  = Ide::get()->getUserConfigValue(get_class($this) . '.width', $screen->bounds['width'] * 0.75);
        $this->height = Ide::get()->getUserConfigValue(get_class($this) . '.height', $screen->bounds['height'] * 0.75);

        if ($this->width < 300 || $this->height < 200) {
            $this->width = $screen->bounds['width'] * 0.75;
            $this->height = $screen->bounds['height'] * 0.75;
        }

        $this->centerOnScreen();

        $this->x = Ide::get()->getUserConfigValue(get_class($this) . '.x', 0);
        $this->y = Ide::get()->getUserConfigValue(get_class($this) . '.y', 0);

        if ($this->x > $screen->visualBounds['width'] - 10 || $this->y > $screen->visualBounds['height'] - 10 ||
            $this->x < -999 || $this->y < -999) {
            $this->x = $this->y = 50;
        }

        $this->maximized = Ide::get()->getUserConfigValue(get_class($this) . '.maximized', true);

        $this->observer('maximized')->addListener(function ($old, $new) {
            Ide::get()->setUserConfigValue(get_class($this) . '.maximized', $new);
        });

        $this->contentSplitPane->items[0]->observer('width')->addListener(function ($old, $new) {
            Ide::get()->setUserConfigValue(get_class($this) . '.dividerPositions', $new);
        });

        foreach (['width', 'height', 'x', 'y'] as $prop) {
            $this->observer($prop)->addListener(function ($old, $new) use ($prop) {
                if ($this->iconified) {
                    return;
                }

                if (!$this->maximized) {
                    Ide::get()->setUserConfigValue(get_class($this) . '.' . $prop, $new);
                }

                Ide::get()->setUserConfigValue(get_class($this) . '.dividerPositions', $this->contentSplitPane->dividerPositions);
            });
        }


       /* $overlay = new UXAnchorPane();
        $overlay->opacity = 0.01;
        UXAnchorPane::setAnchor($overlay, 0);
        $this->layout->add($overlay);*/


        //$this->projectTabs->tabs[0]->graphic = ico('settings16');
        //$this->projectTabs->tabs[1]->graphic = ico('tree16');
    }

    /**
     * @event close
     *
     * @param UXEvent $e
     *
     * @throws \Exception
     * @throws \php\io\IOException
     */
    public function doClose(UXEvent $e = null)
    {
        Logger::info("Close main form ...");

        $project = Ide::get()->getOpenedProject();

        if ($project) {
            $dialog = new MessageBoxForm("Хотите открыть текущий проект ({$project->getName()}) при следующем запуске среды?", [
                'yes' => 'Да, открыть проект',
                'no'  => 'Нет',
                'abort' => 'Отмена, не закрывать среду'
            ]);
            $dialog->title = 'Закрытие проекта';

            if ($dialog->showDialog()) {
                $result = $dialog->getResult();

                if ($result == 'yes') {
                    Logger::info("Remember the last project = yes!");

                    Ide::get()->setUserConfigValue('lastProject', $project->getFile($project->getName() . '.dnproject'));
                } elseif ($result == 'abort') {
                    if ($e) {
                        $e->consume();
                    }
                    return;
                } else {
                    Logger::info("Cancel closing main form.");
                    Ide::get()->setUserConfigValue('lastProject', null);
                }

                Ide::get()->shutdown();
            } else {
                if ($e) {
                    $e->consume();
                }
            }
        } else {
            Ide::get()->setUserConfigValue('lastProject', null);

            $dialog = new MessageBoxForm('Вы уверены, что хотите выйти из среды?', ['Да, выйти', 'Нет']);
            if ($dialog->showDialog() && $dialog->getResultIndex() == 0) {
                $this->hide();

                Ide::get()->shutdown();
            } else {
                if ($e) {
                    $e->consume();
                }
            }
        }
    }

    /**
     * @return UXHBox
     */
    public function getHeadPane()
    {
        return $this->headPane;
    }

    /**
     * @return UXHBox
     */
    public function getHeadRightPane()
    {
        return $this->headRightPane;
    }

    /**
     * @return UXTreeView
     */
    public function getProjectTree()
    {
        return $this->projectTree;
    }

    public function hideBottom()
    {
        $this->showBottom(null);
    }

    public function showBottom(UXNode $content = null)
    {
        if ($content) {
            $this->bottomSpoiler->children->clear();

            $height = $this->layout->height;

            $content->height = (int) Ide::get()->getUserConfigValue('mainForm.consoleHeight', 350);

            $content->observer('height')->addListener(function ($old, $new) use ($content) {
                if (!$content->isFree()) {
                    Ide::get()->setUserConfigValue('mainForm.consoleHeight', $new);
                }
            });

            UXAnchorPane::setAnchor($content, 0);

            $this->bottomSpoiler->add($content);

            $percent = (($content->height + 4) * 100 / $height) / 100;

            $this->contentSplit->dividerPositions = [1 - $percent, $percent];
        } else {
            $this->bottomSpoiler->children->clear();
            $this->contentSplit->dividerPositions = [1, 0];
        }
    }
}