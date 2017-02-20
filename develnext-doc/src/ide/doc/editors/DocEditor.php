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
use ide\forms\area\DocEntryPageArea;
use ide\forms\DocEntryEditForm;
use ide\Ide;
use ide\Logger;
use ide\misc\EventHandlerBehaviour;
use ide\systems\FileSystem;
use ide\ui\Notifications;
use ide\utils\FileUtils;
use ide\utils\Tree;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXScrollPane;
use php\gui\UXListView;
use php\gui\UXNode;
use php\gui\UXTreeItem;
use php\gui\UXTreeView;
use php\io\Stream;
use php\lib\str;
use script\TimerScript;

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
    use EventHandlerBehaviour;

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
     * @var UXScrollPane
     */
    protected $ui;

    /**
     * @var DocEntryListArea
     */
    protected $uiSection;

    /**
     * @var DocEntryPageArea
     */
    protected $uiPage;

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
     * @var array
     */
    protected $openedEntry = null;

    /**
     * @var bool
     */
    protected $anotherPage = false;

    /**
     * @var null
     */
    protected $currentParam = null;

    /**
     * DocEditor constructor.
     * @param string $file
     */
    public function __construct($file)
    {
        parent::__construct($file);

        $this->docService = new DocService();
    }

    public function isAutoClose()
    {
        return false;
    }

    /**
     * @return DocService
     */
    public function getDocService()
    {
        return $this->docService;
    }

    public function refreshTree($loadContent = true)
    {
        $this->docService->categoryTreeAsync(function (ServiceResponse $response) use ($loadContent) {
            if ($response->isSuccess()) {
                /** @var Tree $tree */
                $tree = $response->data();

                if ($this->treeLoaded) {
                    $this->expandedItems = $this->getTreeExpandedItems();
                }

                $selected = $this->getSelectedCategory();

                if (!$this->treeLoaded) {
                    $id = Ide::get()->getUserConfigValue(get_class($this) . '#selectedCategory');

                    if ($id) {
                        $selected = ['id' => $id];
                    }
                }

                $this->uiTreeItemById = [];
                $this->uiTree->root->children->clear();

                $this->updateTree($tree, null, null, $this->expandedItems);

                $this->setSelectedCategory($selected, false, $loadContent);

                if (!$this->treeLoaded) {
                    $id = Ide::get()->getUserConfigValue(get_class($this) . '#openedEntry');

                    if ($id && $loadContent) {
                        $this->openEntry(['id' => $id]);
                    }
                }

                $this->treeLoaded = true;
            }
        });
    }

    protected function loadContent($force = false)
    {
        if ($this->anotherPage) {
            $force = true;
        }

        $item = $this->uiTree->focusedItem;

        $this->ui->content = $this->uiSection;

        $this->openedEntry = null;
        $this->currentParam = null;

        Logger::info("Load category list");

        $this->docService->accessInfoAsync(function (ServiceResponse $response) {
            if ($response->isSuccess()) {
                $this->accessCategory = $response->data()['category'];
                $this->accessEntry = $response->data()['entry'];
                $this->uiSection->setAccess($this->accessCategory, $this->accessEntry);
            } else {
                $this->uiSection->setAccess(false, false);

                if ($response->isConnectionFailed()) {
                    Notifications::warning('Документация недоступна', 'Доступ к документации возможен только при подключении к интернету.');
                } else {
                    Notifications::warning('Сервис временно недоступен', 'Сервис документации временно недоступен, попробуйте позже.');
                }
            }
        });

        $this->uiSection->setContent([
            'name' => 'Документация',
            'description' => 'Добро пожаловать в справочную систему по DevelNext'
        ]);

        $this->anotherPage = false;


        if ($item->value instanceof DocEditorTreeItem) {
            $category = $item->value->getData();
            $this->uiSection->setContent($category);

            if (($category['id'] == $this->loadedCategoryId && $this->loadedCategoryId !== null) && !$force) {
                return;
            }

            $this->uiSection->showPreloader();
            $this->docService->entriesAsync($category['id'], 0, 40, function (ServiceResponse $response) use ($item, $category) {
                $this->uiSection->setAccess($this->accessCategory, $this->accessEntry);
                if ($response->isSuccess()) {
                    $this->uiSection->setContent($item->value->getData(), $response->data());
                }

                $this->uiSection->hidePreloader();
                $this->loadedCategoryId = $category['id'];
                $this->loaded = true;
            });
        } else {
            /*if (null == $this->loadedCategoryId) {
                return;
            }   */

            $this->uiSection->showPreloader();
            $this->docService->allEntriesAsync('UPDATED_AT', 0, 50, function (ServiceResponse $response) {
                if ($response->isSuccess()) {
                    $this->uiSection->setContent([
                        'name' => 'Документация (последнее)',
                        'description' => 'Последние добавленные статьи в документацию'
                    ], $response->data());
                }

                $this->uiSection->hidePreloader();
                $this->loadedCategoryId = null;
                $this->uiSection->setAccess($this->accessCategory, $this->accessEntry);
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

    public function setSelectedCategory(array $data = null, $force = false, $loadContent = true)
    {
        if ($data && $one = $this->uiTreeItemById[$data['id']]) {
            $this->uiTree->focusedItem = $one;
            $this->uiTree->selectedItems = [$one];
        } else {
            $this->uiTree->focusedItem = $this->uiTree->root;
            $this->uiTree->selectedItems = [$this->uiTree->root];
        }

        if ($loadContent) {
            $this->loadContent($force);
        }
    }

    public function open($param = null)
    {
        parent::open($param);

        if ($param) {
            $this->currentParam = $param;

            if ($param['search']) {
                $this->search($param['search']);
                return;
            }
        }

        if ($this->openedEntry) {

        } else {
            $this->refreshTree();
            $this->loadContent();
        }
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
        Ide::get()->setUserConfigValue(__CLASS__ . '#selectedCategory', $this->getSelectedCategory()['id']);
        Ide::get()->setUserConfigValue(__CLASS__ . '#openedEntry', $this->openedEntry['id']);
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

    public function editEntry(array $entry, $fastEdit = false)
    {
        if (!$fastEdit) {
            $this->openEntry($entry);
        }

        $this->docService->entryAsync($entry['id'], function (ServiceResponse $response) {
            if ($response->isSuccess()) {
                $entry = $response->data();
                $dialog = new DocEntryEditForm();
                $dialog->setResult($entry);

                $dialog->events->on('save', function ($entry) {
                    $this->uiPage->showPreloader();

                    $this->docService->saveEntryAsync($entry, function (ServiceResponse $response) {
                        $this->uiPage->hidePreloader();

                        if ($response->isSuccess()) {
                            if ($this->openedEntry) {
                                $this->openEntry($response->data());
                            } else {
                                $this->loadContent(true);
                            }
                        } else {
                            Notifications::error('Ошибка сохранения', $response->message());
                        }
                    });
                });

                $dialog->showDialog();
            } else {
                Notifications::error('Ошибка', $response->message());
            }
        });
    }

    public function openCategory(array $category)
    {
        $this->setSelectedCategory($category, true);
    }

    public function openEntry(array $entry)
    {
        $this->ui->content = $this->uiPage;

        $this->openedEntry = $entry;

        $this->uiPage->showPreloader();

        $this->docService->entryAsync($entry['id'], function (ServiceResponse $response) {
            $this->uiPage->hidePreloader();

            if ($response->isSuccess()) {
                $this->openedEntry = $response->data();
                $this->uiPage->setContent($response->data());
            } else {
                Notifications::error('Ошибка', 'Произошла непредвиденная ошибка');
            }
        });
    }

    public function search($query)
    {
        $searchSection = [
            'name' => "Поиск '$query'",
            'description' => "Поисковая фраза '$query'",
            'icon' => ico('searchEx32'),
        ];

        $this->refreshTree(false);
        $this->ui->content = $this->uiSection;

        $this->anotherPage = true;

        $this->uiSection->setContent($searchSection);
        $this->uiSection->setAccess(false, $this->accessEntry);
        $this->uiSection->showPreloader("Поиск '$query' ...");

        $this->docService->searchAsync($query, 0, 20, function (ServiceResponse $response) use ($searchSection, $query) {
            $this->uiSection->hidePreloader();

            if ($response->isSuccess()) {
                $this->uiSection->setContent($searchSection, $response->data());
            } else {
                Notifications::error('Ошибка поиска', 'Возникла ошибка при попытке сделать поиск. Возможно сервис временно недоступен.');
                Logger::error("Unable to search " . json_encode($query) . ", message = {$response->message()}");
            }
        });
    }

    /**
     * @return UXNode
     */
    public function makeUi()
    {
        $uiSection = new DocEntryListArea();
        $this->uiSection = $uiSection;
        $uiSection->on('addEntry', [$this, 'addEntry']);
        $uiSection->on('deleteEntry', [$this, 'deleteEntry']);
        $uiSection->on('editEntry', [$this, 'editEntry']);
        $uiSection->on('fastEditEntry', function ($entry) {
            $this->editEntry($entry, true);
        });

        $uiSection->on('openCategory', [$this, 'openCategory']);
        $uiSection->on('openEntry', [$this, 'openEntry']);

        UXAnchorPane::setAnchor($uiSection, 0);

        $uiPage = new DocEntryPageArea();
        $this->uiPage = $uiPage;
        $uiPage->on('openCategory', [$this, 'openCategory']);

        $pane = new UXScrollPane($uiSection);
        $pane->fitToWidth = true;
        $pane->fitToHeight = true;
        $pane->classes->add('dn-web');

        return $this->ui = $pane;
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
            new EditCategoryMenuCommand($this),
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