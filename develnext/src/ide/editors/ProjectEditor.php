<?php
namespace ide\editors;

use ide\Ide;
use ide\Logger;
use ide\project\control\AbstractProjectControlPane;
use ide\ui\ListMenu;
use ide\utils\FileUtils;
use php\gui\framework\AbstractForm;
use php\gui\framework\EventBinder;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXVBox;
use php\gui\UXApplication;
use php\gui\UXDesktop;
use php\gui\UXDialog;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXLoader;
use php\gui\UXNode;
use php\gui\UXSeparator;
use php\io\File;
use php\io\ResourceStream;
use php\io\Stream;
use php\lib\arr;
use php\lib\reflect;
use php\lib\str;

class ProjectEditor extends AbstractEditor
{
    /**
     * @var bool
     */
    protected $init = false;

    /**
     * @var AbstractProjectControlPane[]
     */
    protected $controlPanes = [];

    /**
     * @var UXAnchorPane
     */
    protected $contentPane;

    /**
     * @var ListMenu
     */
    protected $menu;

    /**
     * ProjectEditor constructor.
     * @param string $file
     * @param AbstractProjectControlPane[] $controlPanes
     */
    public function __construct($file, array $controlPanes = [])
    {
        parent::__construct($file);

        foreach ($controlPanes as $pane) {
            $this->addControlPane($pane);
        }
    }

    public function addControlPane(AbstractProjectControlPane $pane)
    {
        $this->controlPanes[reflect::typeOf($pane)] = $pane;

        $pane->on('updateCount', function () {
            $this->menu->refresh();
        });
    }

    public function getTitle()
    {
        return 'Проект';
    }

    public function getTabStyle()
    {
        return '-fx-padding: 1px 7px; -fx-font-weight: bold;';
    }

    public function isCloseable()
    {
        return false;
    }

    public function isDraggable()
    {
        return false;
    }

    public function open()
    {
        parent::open();

        $this->menu->refresh();

        if (!$this->getOpenedPane()) {
            $this->navigate(arr::firstKey($this->controlPanes));
        }

        foreach ($this->controlPanes as $pane) {
            $pane->open();
        }
    }

    public function load()
    {
        foreach ($this->controlPanes as $pane) {
            $pane->load();
        }
    }

    public function save()
    {
        foreach ($this->controlPanes as $pane) {
            $pane->save();
        }
    }

    public function close($save = true)
    {
        parent::close($save);

        foreach ($this->controlPanes as $pane) {
            $pane->close();
        }
    }

    public function hide()
    {
        foreach ($this->controlPanes as $pane) {
            $pane->save();
        }
    }

    /**
     * @return AbstractProjectControlPane|null
     */
    public function getOpenedPane()
    {
        return $this->menu->selectedItem;
    }

    public function navigate($paneClass, $setMenu = true)
    {
        if ($pane = $this->controlPanes[$paneClass]) {
            $ui = $pane->getUi();
            $pane->refresh();

            if ($this->contentPane->children[0] != $ui) {
                UXAnchorPane::setAnchor($ui, 0);
                $this->contentPane->children->setAll([$ui]);
            }

            if ($setMenu) {
                $this->menu->selectedIndex = $this->menu->items->indexOf($pane);
            }

            return $pane;
        }

        return null;
    }

    /**
     * @return UXNode
     */
    public function makeUi()
    {
        $pane = new UXAnchorPane();
        $pane->padding = 15;

        return $this->contentPane = $pane;
    }

    public function makeLeftPaneUi()
    {
        $ui = new ListMenu();
        $ui->items->setAll($this->controlPanes);
        UXAnchorPane::setAnchor($ui, 0);

        $ui->on('action', function () {
            uiLater(function () {
                $this->navigate(reflect::typeOf($this->menu->selectedItem), false);
            });
        });

        $this->menu = $ui;

        /*$ui = Ide::project()->getTree()->getRoot();
        $ui->focusTraversable = false;
        UXAnchorPane::setAnchor($ui, 0);*/

        return $ui;
    }
}
