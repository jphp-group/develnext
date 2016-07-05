<?php
namespace ide\store\editors;

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
use ide\store\editors\pages\AbstractStorePage;
use ide\systems\FileSystem;
use ide\ui\ListMenu;
use ide\ui\Notifications;
use ide\utils\FileUtils;
use ide\utils\Tree;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXScrollPane;
use php\gui\layout\UXVBox;
use php\gui\UXImage;
use php\gui\UXImageArea;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\gui\UXListView;
use php\gui\UXNode;
use php\gui\UXSeparator;
use php\gui\UXTreeItem;
use php\gui\UXTreeView;
use php\io\Stream;
use php\lib\reflect;
use php\lib\str;
use script\TimerScript;

class StoreEditor extends AbstractEditor
{
    use EventHandlerBehaviour;

    /**
     * @var ListMenu
     */
    protected $menu;

    /**
     * @var AbstractStorePage[]
     */
    protected $storePages = [];

    /**
     * @var UXAnchorPane
     */
    protected $contentPane;

    /**
     * @var UXHBox
     */
    protected $header;

    public function __construct($file)
    {
        parent::__construct($file);

        foreach (Ide::get()->getInternalList('.dn/storePages') as $one) {
            if (class_exists($one)) {
                $pane = new $one();

                if ($pane instanceof AbstractStorePage) {
                    $this->storePages[$one] = $pane;
                    $pane->on('updateCount', function () {
                        $this->menu->refresh();
                    });
                } else {
                    Logger::error("Unable to register $one store page, class is not instance of AbstractStorePage");
                }
            } else {
                Logger::error("Unable to register $one store page, class not found.");
            }
        }
    }


    public function load()
    {
        foreach ($this->storePages as $pane) {
            $pane->load();
        }
    }

    public function getTitle()
    {
        return "DevelStore";
    }

    public function getTabStyle()
    {
        return '-fx-padding: 1px 7px; -fx-font-weight: bold; -fx-text-fill: #6495ed';
    }

    public function save()
    {
    }

    public function close($save = true)
    {
        parent::close($save);

        foreach ($this->storePages as $pane) {
            $pane->close();
        }
    }

    public function navigate($paneClass, $setMenu = true)
    {
        if ($pane = $this->storePages[$paneClass]) {
            $ui = $pane->getUi();
            $pane->refresh();

            $this->header->lookup('#title')->text = $pane->getName();
            $this->header->lookup('#description')->text = $pane->getDescription();
            $this->header->lookup('#icon')->image = Ide::get()->getImage($pane->getBigIcon())->image;

            if ($this->contentPane->children[0] != $ui) {
                if ($this->contentPane->children->count > 1) {
                    $this->contentPane->children[0] = $ui;
                } else {
                    $this->contentPane->children->add($ui);
                }
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
        $pane = new UXVBox();
        $pane->padding = 15;
        $pane->spacing = 15;
        $pane->backgroundColor = 'white';

        $icon = new UXImageArea();
        $icon->size = [32, 32];
        $icon->centered = true;
        $icon->id = 'icon';

        $titleLabel = new UXLabel('Title');
        $titleLabel->id = 'title';
        $titleLabel->font = $titleLabel->font->withBold();

        $titleDesc = new UXLabel('Description text');
        $titleDesc->id = 'description';
        $titleDesc->textColor = 'gray';

        $title = new UXVBox([$titleLabel, $titleDesc]);
        UXHBox::setHgrow($title, 'ALWAYS');

        $header = new UXHBox([$icon, $title]);
        $header->spacing = 10;

        $pane->add($header);
        $pane->add(new UXSeparator());

        $this->header = $header;

        $content = new UXAnchorPane();
        $scrollPane = new UXScrollPane($content);
        $scrollPane->fitToWidth = true;
        $scrollPane->fitToHeight = true;
        $scrollPane->backgroundColor = 'white';

        UXVBox::setVgrow($scrollPane, 'ALWAYS');
        $pane->add($scrollPane);

        $this->contentPane = $content;

        return $pane;
    }

    public function makeLeftPaneUi()
    {
        $ui = new ListMenu();
        $ui->items->setAll($this->storePages);
        UXAnchorPane::setAnchor($ui, 0);

        $ui->on('action', function () {
            uiLater(function () {
                $this->navigate(reflect::typeOf($this->menu->selectedItem), false);
            });
        });

        $this->menu = $ui;

        return $ui;
    }
}