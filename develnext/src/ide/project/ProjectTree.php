<?php
namespace ide\project;

use ide\editors\menu\ContextMenu;
use ide\Ide;
use ide\systems\FileSystem;
use ide\utils\FileUtils;
use php\gui\event\UXMouseEvent;
use php\gui\UXTreeItem;
use php\gui\UXTreeView;
use php\io\File;

/**
 * Class ProjectTreeItem
 * @package ide\project
 */
class ProjectTreeItem
{
    protected $origin;

    /** @var string */
    private $name;
    private $file;

    /** @var callable */
    protected $onUpdate;

    /**
     * @param string $name
     * @param $file
     */
    public function __construct($name, $file = null)
    {
        $this->name = $name;
        $this->file = $file;

        $this->origin = new UXTreeItem($this);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @param callable $onUpdate
     */
    public function onUpdate($onUpdate)
    {
        $this->onUpdate = $onUpdate;
    }

    /**
     * @return UXTreeItem
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @return \php\gui\UXList
     */
    public function getChildren()
    {
        return $this->origin->children;
    }

    /**
     * Попытка обновить данные.
     */
    public function tryUpdate()
    {
        $onUpdate = $this->onUpdate;

        if ($onUpdate) {
            $onUpdate($this);
        }
    }

    public function add(ProjectTreeItem $item)
    {
        $this->origin->children->add($item->origin);
    }

    public function setExpanded($true)
    {
        $this->origin->expanded = $true;
    }

    public function isExpanded()
    {
        return $this->origin->expanded;
    }

    public function clear()
    {
        $this->origin->children->clear();
    }
}

/**
 * Class ProjectTree
 * @package ide\project
 */
class ProjectTree
{
    /** @var UXTreeView */
    protected $tree;

    /** @var ProjectTreeItem */
    protected $treeProjectItem;

    /**
     * @var ProjectTreeItem[]
     */
    protected $treeProjectItems = [];

    /**
     * @var ContextMenu
     */
    protected $contextMenu;

    /**
     * @var Project
     */
    protected $project;

    /**
     * ProjectTree constructor.
     *
     * @param Project $project
     * @param UXTreeView $tree
     */
    public function __construct(Project $project, UXTreeView $tree)
    {
        $this->tree = $tree;
        $this->project = $project;

        $tree->rootVisible = false;
        $this->contextMenu = new ContextMenu();

        $this->contextMenu->addGroup('add', 'Создать');
        $this->contextMenu->addSeparator();

        $tree->on('mouseUp', [$this, 'doClick']);
        $tree->contextMenu = $this->contextMenu->getRoot();

        $this->clear();
    }

    public function updateDirectory($code, $path)
    {
        $formsItem = $this->getItem($code);
        $formsItem->clear();

        $func = function (ProjectTreeItem $root, $path) use (&$func) {
            foreach (File::of($path)->findFiles() as $file) {
                $item = $this->createItemForFile($file);

                $root->add($item);

                if ($file->isDirectory()) {
                    $func($item, $file);
                }
            }
        };

        $func($formsItem, $path);
    }

    protected function doClick(UXMouseEvent $e)
    {
        /** @var ProjectTreeItem $focused */
        $focused = $this->tree->focusedItem->value;

        if ($focused && $e->clickCount > 1 && $focused->getFile()) {
            FileSystem::open($this->project->getAbsoluteFile($focused->getFile()));
        }
    }

    public function clear()
    {
        if (!$this->treeProjectItem) {
            $this->treeProjectItem = $projectRoot = new ProjectTreeItem('Проект');
            $projectRoot->getOrigin()->expanded = true;

            $this->tree->root = $projectRoot->getOrigin();
        }

        /** @var ProjectTreeItem $item */
        foreach ($this->treeProjectItem->getChildren() as $item) {
            $item->children->clear();
        }
    }

    /**
     * @param string $code
     * @param string $title
     *
     * @param $icon
     *
     * @return ProjectTreeItem
     */
    public function getOrCreateItem($code, $title, $icon = null)
    {
        if ($item = $this->treeProjectItems[$code]) {
            return $item;
        }

        $item = new ProjectTreeItem($title);
        $item->getOrigin()->graphic = Ide::get()->getImage($icon);
        $this->treeProjectItem->add($item);

        $this->treeProjectItems[$code] = $item;

        return $item;
    }

    /**
     * @param $code
     *
     * @return ProjectTreeItem
     */
    public function getItem($code)
    {
        return $this->treeProjectItems[$code];
    }

    /**
     * @return ProjectTreeItem
     */
    public function getTreeProjectItem()
    {
        return $this->treeProjectItem;
    }

    public function update()
    {
        foreach ($this->treeProjectItems as $item) {
            $item->tryUpdate();
        }
    }

    /**
     * @param string|ProjectFile $file
     *
     * @return ProjectTreeItem
     */
    public function createItemForFile($file)
    {
        $name = File::of($file)->getName();

        $format = Ide::get()->getFormat($file);

        $item = new ProjectTreeItem($format ? $format->getTitle($file) : $name, $file);

        if ($format) {
            $item->getOrigin()->graphic = Ide::get()->getImage($format->getIcon());
        } else {
            if (File::of($file)->isDirectory()) {
                $item->getOrigin()->graphic = Ide::get()->getImage('icons/folder16.png');
            }
        }

        return $item;
    }
}