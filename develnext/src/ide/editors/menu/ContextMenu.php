<?php
namespace ide\editors\menu;

use ide\editors\AbstractEditor;
use ide\misc\AbstractCommand;
use php\gui\UXContextMenu;
use php\gui\UXMenu;
use php\gui\UXMenuItem;
use ide\Ide;
use php\lang\IllegalArgumentException;

/**
 * Class ContextMenu
 * @package ide\editors\menu
 */
class ContextMenu
{
    /**
     * @var UXContextMenu
     */
    protected $root;

    /**
     * @var AbstractEditor
     */
    protected $editor;

    /**
     * @var UXMenu[]
     */
    protected $groups = [];

    /**
     * @param AbstractEditor $editor
     * @param array $commands
     */
    public function __construct(AbstractEditor $editor = null, array $commands = [])
    {
        $this->editor = $editor;
        $this->root = new UXContextMenu();

        foreach ($commands as $command) {
            $this->add($command);
        }
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

    public function addCommand(AbstractCommand $command)
    {
        $menuItem = new UXMenuItem($command->getName(), Ide::get()->getImage($command->getIcon()));
        $menuItem->accelerator = $command->getAccelerator();

        $menuItem->on('action', function ($e) use ($command) {
            $command->onExecute();
        });

        $this->root->items->add($menuItem);
    }

    public function add(AbstractMenuCommand $command, $group = null)
    {
        $menuItem = new UXMenuItem($command->getName(), Ide::get()->getImage($command->getIcon()));
        $menuItem->accelerator = $command->getAccelerator();

        if ($command->isHidden()) {
            $menuItem->visible = false;
        }

        $menuItem->on('action', function ($e) use ($command) {
            $command->onExecute($e, $this->editor);
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
}