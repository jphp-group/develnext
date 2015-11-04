<?php
namespace ide\forms;

use Files;
use ide\forms\mixins\DialogFormMixin;
use ide\Ide;
use ide\utils\FileUtils;
use php\gui\event\UXMouseEvent;
use php\gui\framework\AbstractForm;
use php\gui\layout\UXFlowPane;
use php\gui\layout\UXPane;
use php\gui\layout\UXScrollPane;
use php\gui\layout\UXVBox;
use php\gui\paint\UXColor;
use php\gui\UXApplication;
use php\gui\UXFileChooser;
use php\gui\UXImage;
use php\gui\UXImageArea;
use php\gui\UXLabel;
use php\gui\UXLabelEx;
use php\io\File;
use php\lib\Items;
use php\lib\Str;
use php\util\Flow;

/**
 * Class ImagePropertyEditorForm
 * @package ide\forms
 *
 * @property UXScrollPane $imageView
 * @property UXFlowPane $gallery
 */
class ImagePropertyEditorForm extends AbstractForm
{
    use DialogFormMixin {
        DialogFormMixin::setResult as protected _setResult;
    }

    protected $copiedFile = null;

    /** @var UXImageArea */
    protected $imageArea;

    /** @var UXFileChooser */
    protected $dialog;

    protected function init()
    {
        parent::init();

        $dialog = new UXFileChooser();
        $dialog->extensionFilters = [
            ['description' => 'Изображения (jpg, png, gif)', 'extensions' => ['*.jpg', '*.jpeg', '*.png', '*.gif']]
        ];

        $this->dialog = $dialog;

        $imageArea = new UXImageArea();
        $imageArea->centered = true;
        $imageArea->proportional = true;
        $imageArea->stretch = false;
        $imageArea->width = $this->imageView->width - 6;
        $imageArea->height = $this->imageView->height - 6;

        $this->imageView->content = $imageArea;
        $this->imageArea = $imageArea;
    }

    public function updateGallery()
    {
        $this->gallery->children->clear();

        $project = Ide::get()->getOpenedProject();

        FileUtils::scan($project->getFile('src/.data'), function ($filename) use ($project) {
            $file = $project->getAbsoluteFile($filename);

            if (!$file->isFile()) {
                return;
            }

            if (Str::endsWith($filename, ".jpg") || Str::endsWith($filename, ".jpeg")
                || Str::endsWith($filename, ".png") || Str::endsWith($filename, ".gif")) {

                $item = new UXImageArea(new UXImage($filename));

                $item->size = [72, 72];

                $item->centered = true;
                $item->stretch = true;
                $item->smartStretch = true;
                $item->proportional = true;

                $pane = new UXVBox();
                $pane->size = $item->size;
                $pane->padding = 8;
                $pane->classes->add('dn-list-item');

                $pane->children->add($item);

                $nameLabel = new UXLabelEx($file->getName());
                $nameLabel->paddingTop = 5;
                $nameLabel->width = $item->width;
                $nameLabel->tooltipText = $file;

                $pane->children->add($nameLabel);

                $pane->on('click', function (UXMouseEvent $e) use ($file) {
                    $this->setResult($file->getRelativePath('src'));

                    if ($e->clickCount >= 2) {
                        $this->hide();
                    }
                });

                UXApplication::runLater(function () use ($pane) {
                    $this->gallery->children->add($pane);
                });
            }
        });
    }

    public function setResult($path)
    {
        $this->result = $path;

        UXApplication::runLater(function () use ($path) {
            if (Files::exists($path)) {
                $file = $path;
            } else {
                $file = $path ? Ide::get()->getOpenedProject()->getFile("src/$path") : null;
            }

            if (Files::isFile($file)) {
                $image = new UXImage($file);
                $this->imageArea->image = $image;
            } else {
                $image = Ide::get()->getImage('dummyImage.png')->image;
                $this->imageArea->image = $image;
            }

            $this->imageArea->stretch = $image->width > $this->imageArea->width || $image->height > $this->imageArea->height;

            $this->pathField->text = $path;
        });
    }

    /**
     * @event addToGalleryButton.action
     */
    public function actionAddToGallery()
    {
        if ($file = $this->dialog->execute()) {
            $project = Ide::get()->getOpenedProject();

            $files = $project->findDuplicatedFiles($file);

            if ($files) {
                $this->toast('Данное изображение уже есть в проекте');
            } else {
                $file = $project->copyFile($file, 'src/.data/img/');
                $this->setResult($file->getRelativePath('src/'));
                $this->updateGallery();
            }
        }
    }

    /**
     * @event loadButton.action
     */
    public function actionLoad()
    {
        if ($file = $this->dialog->execute()) {
            $files = Ide::get()->getOpenedProject()->findDuplicatedFiles($file);

            if ($files) {
                $file = Items::first($files);
                $this->setResult($file->getRelativePath('src/'));
                $this->toast('Изображение уже есть в проекте, будет использован оригинал из проекта');
            } else {
                $this->setResult($file);
            }
        }
    }

    /**
     * @event clearButton.action
     */
    public function actionClear()
    {
        $this->setResult('');
    }

    /**
     * @event show
     */
    public function actionShow()
    {
        $this->updateGallery();
    }

    /**
     * @event hide
     */
    public function actionHide()
    {
        Ide::get()->setUserConfigValue(__CLASS__ . '#dir', $this->dialog->initialDirectory);
    }

    /**
     * @event applyButton.action
     */
    public function actionApply()
    {
        $path = $this->pathField->text;

        if ($path) {
            $project = Ide::get()->getOpenedProject();

            $file = $project->getFile("src/$path");

            if (Files::isFile($file)) {
                $this->setResult($path);
            } elseif (Files::isFile($path)) {
                $files = $project->findDuplicatedFiles($path);

                if ($files) {
                    $file = Items::first($files);
                } else {
                    $file = $project->copyFile($path, 'src/.data/img/');
                }

                $this->setResult($file->getRelativePath('src/'));
            }
        } else {
            $this->setResult('');
        }

        $this->hide();
    }

    /**
     * @event close
     * @event cancelButton.action
     */
    public function actionCancel()
    {
        $this->setResult(null);
        $this->hide();
    }
}