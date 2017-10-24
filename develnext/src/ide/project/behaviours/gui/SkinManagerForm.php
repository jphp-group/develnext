<?php
namespace ide\project\behaviours\gui;

use ide\entity\ProjectSkin;
use ide\forms\AbstractIdeForm;
use ide\forms\MessageBoxForm;
use ide\forms\mixins\DialogFormMixin;
use ide\Ide;
use ide\library\IdeLibrarySkinResource;
use ide\systems\IdeSystem;
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

        $this->icon->image = ico('brush32')->image;

        $this->list->setCellFactory(function (UXListCell $cell, ?IdeLibrarySkinResource $resource) {
            if ($resource) {
                $cell->text = null;
                if ($skin = $resource->getSkin()) {
                    $cell->graphic = new UXLabel($skin->getName(), ico('brush16'));
                }
            } else {
                $cell->text = '(Без скина)';
                $cell->graphic = ico('brush16');
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
        if (!$this->list->selectedItem) {
            MessageBoxForm::warning('Выберите скин ...');
        } else {
            $this->setResult($this->list->selectedItem->getSkin());
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

        if ($file = $dlg->execute()) {
            try {
                if ($skin = ProjectSkin::createFromZip($file)) {
                    $ideLibrary = Ide::get()->getLibrary();

                    $skinDir = $ideLibrary->getResourceDirectory('skins');
                    FileUtils::copyFile($file, "$skinDir/" . fs::name($file));
                    $ideLibrary->updateCategory('skins');

                    $this->updateList();
                    $this->toast('Скин был успешно добавлен');
                }
            } catch (ZipException $e) {
                MessageBoxForm::warning("Ошибка чтения zip файла, скин не был добавлен.", $this);
            } catch (IOException $e) {
                MessageBoxForm::warning("Ошибка чтения файла, скин не был добавлен", $this);
            }

        }
    }

    public function updateList()
    {
        $this->list->items->setAll([null]);
        $this->list->items->addAll(Ide::get()->getLibrary()->getResources('skins'));
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