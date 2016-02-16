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
use ide\forms\area\DocEntryListArea;
use ide\Ide;
use ide\systems\FileSystem;
use ide\ui\Notifications;
use ide\utils\Tree;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXScrollPane;
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
        return (string)$this->data['name'];
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
     * @var DocEntryListArea
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

    /**
     * @var bool
     */
    protected $treeLoaded = false;

    /**
     * @var null
     */
    protected $loadedCategoryId = -1;

    /**
     * @var bool
     */
    protected $accessCategory = false;

    /**
     * @var bool
     */
    protected $accessEntry = false;

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

    protected function loadContent($force = false)
    {
        $item = $this->uiTree->focusedItem;

        $this->docService->accessInfoAsync(function (ServiceResponse $response) {
            if ($response->isSuccess()) {
                $this->accessCategory = $response->data()['category'];
                $this->accessEntry = $response->data()['entry'];
                $this->ui->setAccess($this->accessCategory, $this->accessEntry);
            } else {
                $this->ui->setAccess(false, false);

                if ($response->isConnectionFailed()) {
                    Notifications::warning('Документация недоступна', 'Доступ к документации возможен только при подключении к интернету.');
                } else {
                    Notifications::warning('Сервис временно недоступен', 'Сервис документации временно недоступен, попробуйте позже.');
                }
            }
        });

        $this->ui->setContent([
            'name' => 'Документация',
            'description' => 'Добро пожаловать в справочную систему по DevelNext'
        ]);


        if ($item->value instanceof DocEditorTreeItem) {
            $category = $item->value->getData();
            $this->ui->setContent($category);

            if ($category['id'] == $this->loadedCategoryId && !$force) {
                return;
            }

            $this->ui->showPreloader();
            $this->docService->entriesAsync($category['id'], 0, 40, function (ServiceResponse $response) use ($item, $category) {
                $this->ui->setAccess($this->accessCategory, $this->accessEntry);
                if ($response->isSuccess()) {
                    $this->ui->setContent($item->value->getData(), $response->data());
                }

                $this->ui->hidePreloader();
                $this->loadedCategoryId = $category['id'];
            });
        } else {
            if (null == $this->loadedCategoryId) {
                return;
            }

            $this->ui->showPreloader();
            $this->docService->allEntriesAsync('UPDATED_AT', 0, 50, function (ServiceResponse $response) {
                if ($response->isSuccess()) {
                    $this->ui->setContent([
                        'name' => 'Документация',
                        'description' => 'Добро пожаловать в справочную систему по DevelNext'
                    ], $response->data());
                }

                $this->ui->hidePreloader();
                $this->loadedCategoryId = null;
                $this->ui->setAccess($this->accessCategory, $this->accessEntry);
            });
        }
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

        $this->loadContent();
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

    public function addEntry($name)
    {
        $category = $this->getSelectedCategory();

        $this->docService->saveEntryAsync([
            'name' => $name,
            'categoryId' => $category['id'],
        ], function (ServiceResponse $response) {
            if ($response->isNotSuccess()) {
                Notifications::error('Ошибка', $response->message());
                return;
            }

            $this->loadContent(true);
        });
    }

    public function deleteEntry(array $entry)
    {
        $this->docService->deleteEntryAsync($entry['id'], function (ServiceResponse $response) {
            if ($response->isSuccess()) {
                Notifications::success('Успешно', 'Удаление прошло успешно');
                $this->loadContent(true);
            } else {
                Notifications::error('Ошибка', $response->message());
            }
        });
    }

    public function editEntry(array $entry)
    {

    }

    public function openCategory(array $category)
    {
        $this->setSelectedCategory($category);
    }

    public function openEntry(array $entry)
    {
        dump($entry);
    }

    /**
     * @return UXNode
     */
    public function makeUi()
    {
        $ui = new DocEntryListArea();
        $ui->on('addEntry', [$this, 'addEntry']);
        $ui->on('deleteEntry', [$this, 'deleteEntry']);
        $ui->on('editEntry', [$this, 'editEntry']);

        $ui->on('openCategory', [$this, 'openCategory']);
        $ui->on('openEntry', [$this, 'openEntry']);

        UXAnchorPane::setAnchor($ui, 0);

        $this->ui = $ui;
        $pane = new UXScrollPane($ui);
        $pane->fitToWidth = true;
        $pane->classes->add('dn-web');
        return $pane;
    }

    public function makeLeftPaneUi()
    {
        $tree = new UXTreeView();
        $tree->root = new UXTreeItem('Документация');
        $tree->rootVisible = true;
        $tree->root->expanded = true;
        $tree->root->graphic = Ide::get()->getImage($this->getIcon());
        $tree->multipleSelection = false;

        $tree->on('mouseUp', function () {
            $this->loadContent();
        });

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

    /**
     * @return boolean
     */
    public function isAccessCategory()
    {
        return $this->accessCategory;
    }

    /**
     * @return boolean
     */
    public function isAccessEntry()
    {
        return $this->accessEntry;
    }
}