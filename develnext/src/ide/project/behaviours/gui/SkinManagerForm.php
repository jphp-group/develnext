<?php
namespace ide\project\behaviours\gui;

use ide\editors\menu\ContextMenu;
use ide\entity\ProjectSkin;
use ide\forms\AbstractIdeForm;
use ide\forms\MessageBoxForm;
use ide\forms\mixins\DialogFormMixin;
use ide\Ide;
use ide\library\IdeLibrary;
use ide\library\IdeLibrarySkinResource;
use ide\misc\SimpleSingleCommand;
use ide\systems\IdeSystem;
use ide\ui\ListExtendedItem;
use ide\utils\FileUtils;
use php\compress\ZipException;
use php\gui\layout\UXStackPane;
use php\gui\layout\UXVBox;
use php\gui\UXFileChooser;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\io\File;
use php\io\IOException;
use php\lib\fs;
use php\lib\str;
use timer\AccurateTimer;

/**
 * Class SkinManagerForm
 * @package ide\project\behaviours\gui
 *
 * @property UXListView $list
 * @property UXImageView $icon
 * @property UXVBox $previewContent
 * @property UXStackPane $previewPane
 */
class SkinManagerForm extends AbstractIdeForm
{
    use DialogFormMixin;

    public function init()
    {
        parent::init();

        $contextMenu = new ContextMenu(null, [
            SimpleSingleCommand::makeWithText('Удалить из библиотеки', 'icons/trash16.gif', function () {
                if ($this->list->selectedIndex > 0) {
                    /** @var IdeLibrarySkinResource $skin */
                    $skin = $this->list->selectedItem;

                    if (MessageBoxForm::confirmDelete("скин {$skin->getSkin()->getName()}")) {
                        Ide::get()->getLibrary()->delete($skin);
                        $this->updateList();
                    }
                } else {
                    MessageBoxForm::warning("Выберите скин для удаления.");
                }
            })
        ]);

        $contextMenu->linkTo($this->list);

        $this->icon->image = ico('brush32')->image;

        $this->list->setCellFactory(function (UXListCell $cell, ?IdeLibrarySkinResource $resource) {
            if ($resource) {
                $cell->text = null;
                if ($skin = $resource->getSkin()) {
                    $desc = $skin->getDescription();

                    if ($skin->getAuthor()) {
                        if ($desc) $desc .= ", ";
                        $desc .= "автор - " . $skin->getAuthor();
                    }

                    if ($skin->getAuthorSite()) {
                        if ($desc) $desc .= " ";
                        $desc .= "({$skin->getAuthorSite()})";
                    }

                    if ($skin->getVersion()) {
                        if ($desc) $desc .= ", ";
                        $desc .= "версия {$skin->getVersion()}";
                    }

                    if (!$desc) {
                        $desc = "Описание отсутствует.";
                    }

                    $cell->graphic = new ListExtendedItem($skin->getName(), str::upperFirst($desc), ico('brush16'));
                }

            } else {
                $cell->text = null;
                $cell->graphic = $ui = new ListExtendedItem('(Без скина)', 'Убрать скин из проекта', ico('brush16'));
                $ui->setTitleThin(true);
            }
        });

        $this->timer = new AccurateTimer(100, function () {
            $this->previewContent->visible = $this->list->selectedIndex > -1;
        });
    }

    /**
     * @event close
     */
    public function doHide()
    {
        $this->timer->stop();
    }

    /**
     * @event cancelButton.action
     */
    public function doCancel(): void
    {
        $this->setResult(null);
        $this->hide();
    }

    /**
     * @event list.click
     */
    public function doListClick()
    {
        $skin = $this->list->selectedItem;

        if ($skin instanceof IdeLibrarySkinResource) {
            $skin = $skin->getSkin();

            $skinDir = Ide::get()->createTempDirectory('skin');
            fs::clean($skinDir);

            $skin->unpack($skinDir);

            $this->previewPane->stylesheets->clear();
            $this->previewPane->stylesheets->add((new File("$skinDir/skin.css"))->toUrl());
        } else {
            $this->previewPane->stylesheets->clear();
        }
    }

    /**
     * @event list.click-2x
     * @event saveButton.action
     */
    public function doSelect()
    {
        if ($this->list->selectedIndex < 0) {
            MessageBoxForm::warning('Выберите скин ...');
        } else {
            $this->setResult($this->list->selectedItem ? $this->list->selectedItem->getSkin() : ProjectSkin::createEmpty());
            $this->hide();
        }
    }

    /**
     * @event addButton.action
     */
    public function doAdd()
    {
        $dlg = new UXFileChooser();
        $dlg->extensionFilters = [['description' => 'Skin Files (*.zip)', 'extensions' => ['*.zip']]];

        retry:
        if ($file = $dlg->execute()) {
            try {
                if ($skin = ProjectSkin::createFromZip($file)) {
                    $ideLibrary = Ide::get()->getLibrary();

                    $skinDir = $ideLibrary->getResourceDirectory('skins');

                    $destFile = "$skinDir/" . $skin->getUid() . ".zip";

                    if (fs::isFile($destFile)) {
                        if (!MessageBoxForm::confirm("Скин с ID '{$skin->getUid()}' уже существует в библиотеке, хотите заменить его новым?")) {
                            goto retry;
                        }
                    }

                    FileUtils::copyFile($file, $destFile);
                    $ideLibrary->updateCategory('skins');

                    $this->updateList();
                    $this->toast('Скин был успешно добавлен');
                }
            } catch (ZipException $e) {
                MessageBoxForm::warning("Ошибка чтения zip файла, скин не был добавлен.", $this);
            } catch (IOException $e) {
                MessageBoxForm::warning("Ошибка чтения файла, скин не был добавлен.", $this);
            }
        }
    }

    public function updateList()
    {
        $this->list->items->setAll([null]);
        $resources = Ide::get()->getLibrary()->getResources('skins');

        foreach ($resources as $resource) {
            if ($resource->isValid()) {
                $this->list->items->add($resource);
            }
        }
    }

    /**
     * @event showing
     */
    public function doShowing(): void
    {
        $this->timer->start();

        $this->updateList();
    }
}