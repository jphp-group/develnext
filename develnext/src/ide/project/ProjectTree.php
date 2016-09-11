<?php
namespace ide\project;

use Files;
use ide\editors\menu\ContextMenu;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\misc\SimpleSingleCommand;
use ide\project\tree\AbstractProjectTreeNavigation;
use ide\systems\FileSystem;
use ide\utils\FileUtils;
use php\gui\UXDesktop;
use php\gui\event\UXMouseEvent;
use php\gui\UXApplication;
use php\gui\UXDialog;
use php\gui\UXTreeItem;
use php\gui\UXTreeView;
use php\io\File;
use php\lib\Str;
use php\util\Regex;

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

    /** @var bool */
    private $disableDelete = false;

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
     * @param boolean $disableDelete
     */
    public function setDisableDelete($disableDelete)
    {
        $this->disableDelete = $disableDelete;
    }

    /**
     * @return boolean
     */
    public function isDisableDelete()
    {
        return $this->disableDelete;
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

    public function insert($index, ProjectTreeItem $item)
    {
        $this->origin->children->insert($index, $item->origin);
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
     * @var Regex[]
     */
    protected $ignoreList = [];

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
        $this->contextMenu->getRoot()->on('show', [$this, 'doRequestContextMenu']);
        $tree->contextMenu = $this->contextMenu->getRoot();
        $this->contextMenu->addSeparator();

        $tree->on('mouseUp', [$this, 'doClick']);

        $this->clear();
    }

    protected function doRequestContextMenu()
    {
        $this->contextMenu->clear();

        $item = $this->tree->focusedItem;

        if ($item) {
            /** @var ProjectTreeItem $item */
            $item = $item->value;

            if (Files::exists($item->getFile())) {
                $this->contextMenu->addCommand(new SimpleSingleCommand('Открыть в проводнике', 'icons/open16.png', function () use ($item) {
                    $desktop = new UXDesktop();
                    $file = File::of($item->getFile());

                    if ($file->isFile()) {
                        $desktop->open($file->getParent());
                    } else {
                        $desktop->open($file);
                    }
                }));

                $this->contextMenu->addSeparator();

                if (!$item->isDisableDelete()) {
                    $this->contextMenu->addCommand(new SimpleSingleCommand('Удалить', 'icons/delete16.png', function () use ($item) {
                        $file = $item->getFile();

                        if (!MessageBoxForm::confirmDelete($file)) {
                            return;
                        }

                        FileSystem::close($file);

                        $format = Ide::get()->getFormat($file);

                        if ($format) {
                            if ($format->delete($file) === false) {
                                return;
                            }

                            waitAsync(1000, function () use ($format, $file) {
                                $format->delete($file);

                                if (Ide::project()) {
                                    Ide::project()->trigger('updateSettings');
                                }
                            });
                        }

                        if ($file instanceof ProjectFile) {
                            $file->delete();
                            $done = !Files::exists($file);
                        } else {
                            if (Files::isDir($file)) {
                                FileUtils::deleteDirectory($file);
                                $done = !Files::isDir($file);
                            } else {
                                File::of($file)->delete();
                                $done = !Files::isFile($file);
                            }
                        }

                        if (!$done) {
                            UXDialog::show('Ошибка удаления, что-то пошло не так', 'ERROR');
                        } else {
                            $this->project->update();
                        }
                    }));
                }
            }

            /*$this->contextMenu->addGroup('add', 'Создать');
            $this->contextMenu->addSeparator(); */
        }
    }

    public function addIgnoreRule($regex)
    {
        $this->ignoreList[$regex] = Regex::of($regex);
    }

    /**
     * @param string|ProjectTreeItem $code
     * @param $path
     * @param bool $saveSelected
     */
    public function updateDirectory($code, $path, $saveSelected = true)
    {
        if (UXApplication::isShutdown()) return;

        $selected = $this->tree->selectedItems;
        $focused = $this->tree->focusedItem;

        if ($code instanceof ProjectTreeItem) {
            $formsItem = $code;
        } else {
            $formsItem = $this->getItem($code);
            $formsItem->clear();
        }

        $files = [];

        foreach ($formsItem->getChildren() as $item) {
            /** @var ProjectTreeItem $e */
            $e = $item->value;

            $file = $this->project->getAbsoluteFile($e->getFile());

            $ignore = false;

            foreach ($this->ignoreList as $regex) {
                if (Regex::match($regex->getPattern(), $file->getRelativePath())) {
                    $ignore = true;
                    break;
                }
            }

            if (!$file->exists() || $file->isHiddenInTree() || $ignore) {
                $formsItem->getOrigin()->children->remove($item);
            } else if ($file->isDirectory()) {
                $this->updateDirectory($e, $e->getFile(), false);
            } else {
                $formsItem->getOrigin()->children->clear();
            }

            $files[FileUtils::hashName($file)] = $file;
        }

        foreach (File::of($path)->findFiles() as $file) {
            $file = $this->project->getAbsoluteFile($file);

            if ($file->isHiddenInTree()) continue;

            if (!$files[FileUtils::hashName($file)]) {
                $ignore = false;

                foreach ($this->ignoreList as $regex) {
                    if (Regex::match($regex->getPattern(), $file->getRelativePath())) {
                        $ignore = true;
                        break;
                    }
                }

                if ($ignore) continue;
            }

            $children = $formsItem->getChildren();
            $item = $this->createItemForFile($file);

            if ($children->count()) {
                $added = false;

                foreach ($children as $i => $el) {
                    $name = "{$el->value}";

                    if ($file->isDirectory()) {
                        $formsItem->insert($i, $item);
                        $added = true;
                        break;
                    }

                    if (Str::compare($file->getName(), $name) < 0) {
                        $formsItem->insert($i, $item);
                        $added = true;
                        break;
                    }
                }

                if (!$added) $formsItem->add($item);
            } else {
                $formsItem->add($item);
            }

            if ($file->isDirectory()) {
                $this->updateDirectory($item, $file, false);
            }
        }

        $formsItem->getOrigin()->update();

        if ($saveSelected) {
            UXApplication::runLater(function () use ($selected, $focused) {
                $this->tree->selectedItems = $selected;
                $this->tree->focusedItem = $focused;
            });
        }
    }

    protected function doClick(UXMouseEvent $e)
    {
        /** @var ProjectTreeItem $focused */
        if ($this->tree->focusedItem) {
            $focused = $this->tree->focusedItem->value;

            if ($focused && $e->clickCount > 1 && $focused->getFile()) {
                FileSystem::open($this->project->getAbsoluteFile($focused->getFile()));
            }
        }
    }

    public function clear($absolutely = false)
    {
        if (!$this->treeProjectItem) {
            $this->treeProjectItem = $projectRoot = new ProjectTreeItem('Проект');
            $projectRoot->getOrigin()->expanded = true;

            $this->tree->root = $projectRoot->getOrigin();
        }

        if ($absolutely) {
            $this->tree->root->children->clear();
            $this->treeProjectItems = [];
        } else {
            /** @var ProjectTreeItem $item */
            foreach ($this->treeProjectItem->getChildren() as $item) {
                $item->children->clear();
            }
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
    public function getOrCreateItem($code, $title, $icon = null, $file = null)
    {
        if ($item = $this->treeProjectItems[$code]) {
            return $item;
        }

        $item = new ProjectTreeItem($title, $file);
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

    public function register(AbstractProjectTreeNavigation $navigation)
    {
        $navigation->setProject($this->project);

        $item = $this->getOrCreateItem(
            get_class($navigation), $navigation->getName(), Ide::get()->getImage($navigation->getIcon()), $this->project->getFile($navigation->getPath())
        );

        $item->setDisableDelete(true);
        $item->setExpanded(true);

        $item->onUpdate(function () use ($navigation, $item) {
            $items = $navigation->getItems();

            $item->clear();

            foreach ($items as $one) {
                $item->add($one);
            }
        });
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

    /**
     * @return UXTreeView
     */
    public function getRoot()
    {
        return $this->tree;
    }
}