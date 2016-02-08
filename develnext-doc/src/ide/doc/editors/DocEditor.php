<?php
namespace ide\doc\editors;

use ide\account\api\ServiceResponse;
use ide\doc\account\api\DocService;
use ide\doc\editors\commands\AddCategoryMenuCommand;
use ide\doc\editors\commands\AddSubCategoryMenuCommand;
use ide\doc\editors\commands\DeleteCategoryMenuCommand;
use ide\doc\editors\commands\EditCategoryMenuCommand;
use ide\editors\AbstractEditor;
use ide\editors\menu\ContextMenu;
use ide\Ide;
use ide\utils\Tree;
use php\gui\layout\UXAnchorPane;
use php\gui\UXListView;
use php\gui\UXNode;
use php\gui\UXTreeItem;
use php\gui\UXTreeView;

class DocEditorTreeItem
{
    /**
     * @var array
     */
    protected $data;

    /**
     * DocEditorTreeItem constructor.
     * @param $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function __toString()
    {
        return (string) $this->data['name'];
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}

class DocEditor extends AbstractEditor
{
    /**
     * @var DocService
     */
    protected $docService;

    /**
     * @var UXTreeView
     */
    protected $uiTree;

    /**
     * @var UXListView
     */
    protected $uiList;

    /**
     * @var ContextMenu
     */
    protected $uiTreeMenu;

    /**
     * @var UXNode
     */
    protected $ui;

    /**
     * @var UXTreeItem[]
     */
    protected $uiTreeItemById = [];

    /**
     * @var array
     */
    protected $expandedItems;

    protected $treeLoaded = false;

    /**
     * DocEditor constructor.
     * @param string $file
     */
    public function __construct($file)
    {
        parent::__construct($file);

        $this->docService = new DocService();
    }

    /**
     * @return DocService
     */
    public function getDocService()
    {
        return $this->docService;
    }

    public function refreshTree()
    {
        $this->docService->categoryTreeAsync(function (ServiceResponse $response) {
            if ($response->isSuccess()) {
                /** @var Tree $tree */
                $tree = $response->data();

                if ($this->treeLoaded) {
                    $this->expandedItems = $this->getTreeExpandedItems();
                }

                $selected = $this->getSelectedCategory();

                $this->uiTreeItemById = [];
                $this->uiTree->root->children->clear();

                $this->updateTree($tree, null, null, $this->expandedItems);

                $this->setSelectedCategory($selected);

                $this->treeLoaded = true;
            }
        });
    }

    protected function loadContent()
    {
        $this->docService->allEntriesAsync('UPDATED_AT', 0, 50, function () {

        });
    }

    protected function getTreeExpandedItems()
    {
        $result = [];

        /** @var UXTreeItem $it */
        foreach ($this->uiTreeItemById as $it) {
            if ($it->expanded && $it->value instanceof DocEditorTreeItem) {
                $id = $it->value->getData()['id'];
                $result[$id] = $id;
            }
        }

        return $result;
    }

    protected function updateTree(Tree $tree, $parentId = null, UXTreeItem $root = null, array $expandedItems = [])
    {
        if ($root == null) {
            $root = $this->uiTree->root;
        }

        //var_dump($tree->getList());
        foreach ($tree->getSub($parentId) as $it) {
            $one = new UXTreeItem(new DocEditorTreeItem($it));
            $one->graphic = ico('open16');

            $root->children->add($one);

            if ($it['id']) {
                $this->uiTreeItemById[$it['id']] = $one;
                $one->expanded = isset($expandedItems[$it['id']]);
                $this->updateTree($tree, $it['id'], $one, $expandedItems);
            }
        }
    }

    /**
     * @return array|null
     */
    public function getSelectedCategory()
    {
        $item = $this->uiTree->focusedItem;

        if ($item && $item->value instanceof DocEditorTreeItem) {
            return $item->value->getData();
        }

        return null;
    }

    public function setSelectedCategory(array $data = null)
    {
        if ($data && $one = $this->uiTreeItemById[$data['id']]) {
            $this->uiTree->focusedItem = $one;
            $this->uiTree->selectedItems = [$one];
        } else {
            $this->uiTree->focusedItem = $this->uiTree->root;
            $this->uiTree->selectedItems = [$this->uiTree->root];
        }
    }

    public function open()
    {
        parent::open();

        $this->refreshTree();
        $this->loadContent();
    }

    public function load()
    {
        $this->expandedItems = Ide::get()->getUserConfigArrayValue(__CLASS__ . "#treeExpandedItems", []);
        $this->expandedItems = array_combine($this->expandedItems, $this->expandedItems);
    }

    public function save()
    {
        // nop.
        Ide::get()->setUserConfigValue(__CLASS__ . '#treeExpandedItems', $this->getTreeExpandedItems());
    }

    /**
     * @return UXNode
     */
    public function makeUi()
    {
        $list = new UXListView();
        UXAnchorPane::setAnchor($list, 10);

        $this->uiList = $list;

        return $list;
    }

    public function makeLeftPaneUi()
    {
        $tree = new UXTreeView();
        $tree->root = new UXTreeItem('Документация');
        $tree->rootVisible = true;
        $tree->root->expanded = true;
        $tree->root->graphic = Ide::get()->getImage($this->getIcon());
        $tree->multipleSelection = false;

        UXAnchorPane::setAnchor($tree, 0);

        $this->uiTree = $tree;

        $this->uiTreeMenu = new ContextMenu($this, [
            new AddCategoryMenuCommand(),
            new AddSubCategoryMenuCommand(),
            new DeleteCategoryMenuCommand(),
            new EditCategoryMenuCommand(),
        ]);
        $this->uiTreeMenu->linkTo($tree);

        return $tree;
    }
}