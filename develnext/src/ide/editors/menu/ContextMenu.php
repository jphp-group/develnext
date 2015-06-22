<?php
namespace ide\editors\menu;
use ide\editors\AbstractEditor;
use php\gui\UXContextMenu;
use php\gui\UXDialog;
use php\gui\UXMenuItem;
use ide\Ide;

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
     * @param AbstractEditor $editor
     * @param array $commands
     */
    public function __construct(AbstractEditor $editor, array $commands = [])
    {
        $this->editor = $editor;
        $this->root = new UXContextMenu();

        foreach ($commands as $command) {
            $this->add($command);
        }
    }

    public function add(AbstractMenuCommand $command)
    {
        $menuItem = new UXMenuItem($command->getName(), Ide::get()->getImage($command->getIcon()));
        $menuItem->accelerator = $command->getAccelerator();

        if ($command->isHidden()) {
            $menuItem->visible = false;
        }

        $menuItem->on('action', function ($e) use ($command) {
            $command->onExecute($e, $this->editor);
        });

        $this->root->items->add($menuItem);

        if ($command->withSeparator()) {
            $this->root->items->add(UXMenuItem::createSeparator());
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