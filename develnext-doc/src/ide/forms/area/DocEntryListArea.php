<?php
namespace ide\forms\area;

use ide\editors\AbstractEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\editors\menu\ContextMenu;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\misc\EventHandlerBehaviour;
use php\gui\framework\AbstractFormArea;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\text\UXFont;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\gui\UXHyperlink;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\gui\UXMenuItem;
use php\gui\UXTextField;
use php\time\Time;
use php\time\TimeZone;

/**
 * Class DocEntryListArea
 * @package ide\forms\area
 *
 * @property UXImageView $categoryIcon
 * @property UXLabel $categoryNameLabel
 * @property UXLabel $categoryDescriptionLabel
 * @property UXVBox $categoryContent
 * @property UXVBox $content
 * @property UXHBox $moderPane
 * @property UXTextField $searchField;
 */
class DocEntryListArea extends AbstractFormArea
{
    use EventHandlerBehaviour;

    protected $category = [];
    protected $entries = [];

    protected $accessCategory = false;
    protected $accessEntry = false;

    /**
     * @var array
     */
    protected $selectEntry;

    public function __construct()
    {
        parent::__construct();

        $this->contextMenu = new ContextMenu(null, [
            new DocEntryListAreaEditEntryCommand($this),
            new DocEntryListAreaDeleteEntryCommand($this),
        ]);
    }

    public function setContent(array $category, array $entries = null)
    {
        $this->category = $category;

        if ($entries !== null) {
            $this->entries = $entries;
        }

        $this->updateUi();
    }

    public function updateUi()
    {
        Ide::service()->media()->loadImage($this->category['icon'], $this->categoryIcon, 'icons/help32.png');

        $this->categoryNameLabel->text = $this->category['name'] ?: 'Неизвестная категория';
        $this->categoryDescriptionLabel->text = $this->category['description'] ?: 'Описание отсутствует ...';

        $this->categoryContent->children->clear();

        foreach ($this->entries as $entry) {
            $uiItem = $this->makeEntryUi($entry);
            $this->categoryContent->add($uiItem);
        }

        if (!$this->categoryContent->children->count) {
            $this->categoryContent->add($this->makeNoEntriesUi());
        }
    }

    protected function makeEntryUi(array $entry)
    {
        $ui = new UXVBox();
        $ui->classes->add('doc-entry');
        $ui->spacing = 0;

        $nameLabel = new UXHyperlink($entry['name']);
        $nameLabel->font = UXFont::of($nameLabel->font, '13', 'BOLD');

        $nameLabel->on('action', function () use ($entry) {
            $this->trigger('openEntry', [$entry]);
        });

        $ui->add($nameLabel);

        $descriptionLabel = new UXLabel($entry['description']);
        $descriptionLabel->textColor = 'gray';

        if (!$descriptionLabel->text) {
            $descriptionLabel->text = 'Данная статья без короткого описания, все ясно из её заголовка ...';
        } else {
            $ui->add($descriptionLabel);
        }

        $hints = new UXHBox();
        $hints->padding = [3, 0];
        $hints->spacing = 7;
        $hints->style = '-fx-font-size: 0.9em;';

        if ($entry['category'] && $this->category['id'] != $entry['category']['id']) {
            $categoryLink = new UXHyperlink($entry['category']['name']);
            $category = new UXHBox([new UXLabel('Категория: '), $categoryLink]);

            $hints->add($category);

            $categoryLink->on('action', function () use ($entry) {
                $this->trigger('openCategory', [$entry['category']]);
            });
        }

        if ($entry['updatedAt']) {
            $time = new Time($entry['updatedAt']);
            $updatedAtValue = new UXLabel($time->toString('dd.MM.yyyy'));
            $updatedAtValue->style = '-fx-font-weight: bold;';
            $updatedAt = new UXHBox([new UXLabel('Дата: '), $updatedAtValue]);

            $hints->add($updatedAt);
        }

        if ($hints->children->count()) {
            $ui->add($hints);
        }

        $handler = function () use ($entry) {
            $this->selectEntry = $entry;
        };
        $nameLabel->on('mouseDown', $handler);
        $ui->on('mouseDown', $handler);

        $this->contextMenu->linkTo($nameLabel);
        $this->contextMenu->linkTo($ui);

        return $ui;
    }

    protected function makeNoEntriesUi()
    {
        $label = new UXLabel('Информации пока нет, загляните сюда позже ...');
        $label->classes->add('dn-list-hint');

        return $label;
    }

    public function setAccess($accessCategory, $accessEntry)
    {
        $this->accessCategory = $accessCategory;
        $this->accessEntry = $accessEntry;

        if ($accessCategory || $accessEntry) {
            if (!$this->moderPane->parent) {
                $this->content->add($this->moderPane);
            }
        } else {
            $this->{'moderPane'} = $this->moderPane;
            $this->moderPane->free();
        }
    }

    /**
     * @event addEntryButton.action
     */
    public function doAddEntry()
    {
        $text = UXDialog::input('Название статьи');

        if ($text) {
            $this->trigger('addEntry', [$text]);
        }
    }

    /**
     * @event searchField.keyDown-Enter
     * @event searchButton.action
     */
    public function doSearch()
    {
        $text = $this->searchField->text;

        if ($text) {
            $this->trigger('search', [$text]);
        }
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

    /**
     * @return array
     */
    public function getSelectEntry()
    {
        return $this->selectEntry;
    }
}

abstract class DocEntryListAreaMenuCommand extends AbstractMenuCommand
{
    /** @var DocEntryListArea */
    protected $area;

    /**
     * DocEntryListAreaEditEntryCommand constructor.
     * @param DocEntryListArea $area
     */
    public function __construct(DocEntryListArea $area)
    {
        $this->area = $area;
    }
}

class DocEntryListAreaEditEntryCommand extends DocEntryListAreaMenuCommand
{
    public function getIcon()
    {
        return 'icons/edit16.png';
    }

    public function getName()
    {
        return 'Редактировать';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $this->area->trigger('editEntry', [$this->area->getSelectEntry()]);
    }

    public function onBeforeShow(UXMenuItem $item, AbstractEditor $editor = null)
    {
        $item->visible = ($this->area->isAccessCategory());
        $item->disable = !$this->area->getSelectEntry();
    }
}


class DocEntryListAreaDeleteEntryCommand extends DocEntryListAreaMenuCommand
{
    public function getIcon()
    {
        return 'icons/delete16.png';
    }

    public function getName()
    {
        return 'Удалить';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        if (MessageBoxForm::confirmDelete($this->area->getSelectEntry()['name'])) {
            $this->area->trigger('deleteEntry', [$this->area->getSelectEntry()]);
        }
    }

    public function onBeforeShow(UXMenuItem $item, AbstractEditor $editor = null)
    {
        $item->visible = ($this->area->isAccessCategory());
        $item->disable = !$this->area->getSelectEntry();
    }
}