<?php
namespace ide\forms\area;

use ide\Ide;
use ide\misc\EventHandlerBehaviour;
use php\gui\framework\AbstractFormArea;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\text\UXFont;
use php\gui\UXDialog;
use php\gui\UXHyperlink;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\gui\UXTextField;

/**
 * Class DocEntryListArea
 * @package ide\forms\area
 *
 * @property UXImageView $categoryIcon
 * @property UXLabel $categoryNameLabel
 * @property UXLabel $categoryDescriptionLabel
 * @property UXVBox $categoryContent
 * @property UXHBox $moderPane
 * @property UXTextField $searchField;
 */
class DocEntryListArea extends AbstractFormArea
{
    use EventHandlerBehaviour;

    protected $category = [];
    protected $entries = [];

    public function setContent(array $category, array $entries)
    {
        $this->category = $category;
        $this->entries = $entries;

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
        $ui->spacing = 5;

        $nameLabel = new UXHyperlink($entry['name']);
        $nameLabel->font = UXFont::of($nameLabel->font, '13', 'BOLD');

        $ui->add($nameLabel);


        $descriptionLabel = new UXLabel($entry['description']);
        $descriptionLabel->textColor = 'gray';

        $ui->add($descriptionLabel);

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
        $this->moderPane->visible = $accessCategory || $accessEntry;

        $this->moderPane->height = $this->moderPane->visible ? 30 : 0;
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
}