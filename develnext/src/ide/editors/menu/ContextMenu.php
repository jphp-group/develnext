<?php
namespace ide\editors\menu;

use action\Geometry;
use ide\editors\AbstractEditor;
use ide\misc\AbstractCommand;
use ide\misc\EventHandlerBehaviour;
use php\desktop\Mouse;
use php\gui\event\UXKeyEvent;
use php\gui\event\UXMouseEvent;
use php\gui\UXContextMenu;
use php\gui\UXMenu;
use php\gui\UXMenuItem;
use ide\Ide;
use php\gui\UXNode;
use php\lang\IllegalArgumentException;
use php\lib\str;

/**
 * Class ContextMenu
 * @package ide\editors\menu
 */
class ContextMenu
{
    use EventHandlerBehaviour;

    /**
     * @var UXContextMenu
     */
    protected $root;

    /**
     * @var AbstractEditor
     */
    protected $editor;

    /**
     * @var callable
     */
    protected $filter;

    /**
     * @var UXMenu[]
     */
    protected $groups = [];

    /**
     * @var string
     */
    protected $style;

    /**
     * @var string
     */
    protected $cssClass;

    /**
     * @param AbstractEditor $editor
     * @param array $commands
     */
    public function __construct(AbstractEditor $editor = null, array $commands = [])
    {
        $this->editor = $editor;
        $this->root = new UXContextMenu();
        $this->root->on('showing', [$this, 'doShowing']);

        foreach ($commands as $command) {
            $this->add($command);
        }
    }

    /**
     * @return string
     */
    public function getCssClass()
    {
        return $this->cssClass;
    }

    /**
     * @param string $cssClass
     */
    public function setCssClass($cssClass)
    {
        $this->cssClass = $cssClass;
        $this->getRoot()->classes->add($cssClass);
    }

    /**
     * @return string
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @param string $style
     */
    public function setStyle($style)
    {
        $this->style = $style;
    }

    /**
     * @return callable
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param callable $filter
     */
    public function setFilter(callable $filter)
    {
        $this->filter = $filter;
    }

    public function clear()
    {
        $this->root->items->clear();
    }

    public function addSeparator($group = null)
    {
        $menu = $this->root;

        if ($group) {
            $menu = $this->groups[$group];

            if (!$menu) {
                throw new IllegalArgumentException("Group $group not found");
            }
        }

        $menu->items->add(UXMenuItem::createSeparator());
    }

    public function addGroup($code, $title, $icon = null)
    {
        $menuItem = new UXMenu($title, Ide::get()->getImage($icon));
        $this->root->items->add($menuItem);

        $this->groups[$code] = $menuItem;
    }

    protected function isCursorInPopup()
    {
        return Geometry::hasPoint($this->root, Mouse::x(), Mouse::y()) || !$this->root->visible;
    }

    public function addCommand(AbstractCommand $command)
    {
        if ($command->withBeforeSeparator()) {
            $this->root->items->add(UXMenuItem::createSeparator());
        }

        $menuItem = $command->makeMenuItem();

        if ($this->cssClass) {
            $menuItem->classes->add("$this->cssClass-item");
        }

        if ($this->style) {
            $menuItem->style .= ';' . $this->style;
        }

        $menuItem->on('action', function ($e) use ($command, $menuItem) {
            $filter = $this->filter;

            if ($this->isCursorInPopup()) {
                if (!$filter || $filter($command) || (!$menuItem->visible && !$menuItem->disable)) {
                    $command->onExecute($e, $this->editor);
                }
            }
        });

        $this->root->items->add($menuItem);

        if ($command->withAfterSeparator()) {
            $this->root->items->add(UXMenuItem::createSeparator());
        }
    }

    public function add(AbstractMenuCommand $command, $group = null)
    {
        $command->setContextMenu($this);

        $menuItem = new UXMenuItem($command->getName(), Ide::get()->getImage($command->getIcon()));
        $menuItem->accelerator = $command->getAccelerator();
        $menuItem->userData = $command;

        if ($this->cssClass) {
            $menuItem->classes->add("$this->cssClass-item");
        }

        if ($this->style) {
            $menuItem->style .= ';' . $this->style;
        }

        if ($command->isHidden()) {
            $menuItem->visible = false;
        }

        $menuItem->on('action', function ($e) use ($command, $menuItem) {
            $filter = $this->filter;

            if ($this->isCursorInPopup()) {
                if (!$filter || $filter($command) || (!$menuItem->visible && !$menuItem->disable)) {
                    $command->onExecute($e, $this->editor);
                }
            }
        });

        $menu = $this->root;

        if ($group) {
            $menu = $this->groups[$group];

            if (!$menu) {
                throw new IllegalArgumentException("Group $group not found");
            }
        }

        $menu->items->add($menuItem);

        if ($command->withSeparator()) {
            $menu->items->add(UXMenuItem::createSeparator());
        }
    }

    /**
     * @return \php\gui\UXContextMenu
     */
    public function getRoot()
    {
        return $this->root;
    }

    public function doShowing()
    {
        $this->trigger('showing');

        foreach ($this->root->items as $item) {
            if ($item && $item->userData instanceof AbstractMenuCommand) {
                $item->userData->onBeforeShow($item, $this->editor);
            }
        }

        foreach ($this->groups as $menu) {
            foreach ($menu->items as $item) {
                if ($item->userData instanceof AbstractMenuCommand) {
                    $item->userData->onBeforeShow($item, $this->editor);
                }
            }
        }

        uiLater(function () {
            $this->trigger('show');
        });
    }

    /**
     * Link context menu to node.
     * @param UXNode $node
     */
    public function linkTo(UXNode $node)
    {
        $handle = function (UXMouseEvent $e) use ($node) {
            if ($e->button == 'SECONDARY') {
                if ($this->root->visible) {
                    $this->root->hide();
                }

                uiLater(function () use ($node) {
                    $this->root->show($node->form, Mouse::x(), Mouse::y());
                });
            }
        };

        $node->on('keyUp', function (UXKeyEvent $e) {
            foreach ($this->root->items as $item) {
                if ($item && $item->userData instanceof AbstractCommand) {
                    if ($e->matches($item->userData->getAccelerator())) {
                        $item->userData->onExecute($e, $this->editor);
                        break;
                    }
                }
            }

            foreach ($this->groups as $menu) {
                foreach ($menu->items as $item) {
                    if ($e->matches($item->userData->getAccelerator())) {
                        $item->userData->onExecute($e, $this->editor);
                        break;
                    }
                }
            }
        }, __CLASS__);


        $node->on('click', $handle, str::uuid());
    }
}