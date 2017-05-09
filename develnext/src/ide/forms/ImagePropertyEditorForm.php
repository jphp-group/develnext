<?php
namespace ide\forms;

use Files;
use ide\account\ui\NeedAuthPane;
use ide\forms\area\IconSearchPaneArea;
use ide\forms\mixins\DialogFormMixin;
use ide\forms\mixins\SavableFormMixin;
use ide\Ide;
use ide\systems\Cache;
use ide\systems\DialogSystem;
use ide\ui\FlowListViewDecorator;
use ide\ui\ImageBox;
use ide\ui\Notifications;
use ide\utils\FileUtils;
use php\gui\event\UXMouseEvent;
use php\gui\framework\AbstractForm;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXFlowPane;
use php\gui\layout\UXPane;
use php\gui\layout\UXScrollPane;
use php\gui\layout\UXVBox;
use php\gui\paint\UXColor;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXFileChooser;
use php\gui\UXImage;
use php\gui\UXImageArea;
use php\gui\UXLabel;
use php\gui\UXLabelEx;
use php\gui\UXNode;
use php\gui\UXTextField;
use php\io\File;
use php\lib\arr;
use php\lib\fs;
use php\lib\Items;
use php\lib\Str;
use php\util\Flow;

/**
 * Class ImagePropertyEditorForm
 * @package ide\forms
 *
 * @property UXAnchorPane $imageView
 * @property UXFlowPane $gallery
 * @property UXAnchorPane $onlineSearchPane
 * @property UXTextField $projectQueryField
 * @property UXButton $projectSearchButton
 */
class ImagePropertyEditorForm extends AbstractIdeForm
{
    use DialogFormMixin {
        DialogFormMixin::setResult as protected _setResult;
    }
    use SavableFormMixin;

    protected $copiedFile = null;

    /** @var UXImageArea */
    protected $imageArea;

    protected $searchPaneArea;

    /** @var UXFileChooser */
    protected $dialog;

    /**
     * @var FlowListViewDecorator
     */
    protected $projectGallery;

    protected function init()
    {
        parent::init();

        $this->gallery->backgroundColor = 'white';
        $this->projectGallery = new FlowListViewDecorator($this->gallery);
        $this->projectGallery->replaceInParent();

        $this->projectGallery->on('beforeRemove', function (array $nodes) {
            if (!MessageBoxForm::confirmDelete('выделенные изображения', $this)) {
                return true;
            }

            return false;
        });

        $this->projectGallery->on('remove', function (array $nodes) {
            /** @var UXNode $node */
            foreach ($nodes as $node) {
                $file = $node->data('file');

                if (!fs::delete($file)) {
                    Notifications::errorDeleteFile($file);
                }
            }

            $this->updateGallery();
        });

        $this->searchPaneArea = new IconSearchPaneArea();
        $this->searchPaneArea->on('action', function ($file) {
            $this->setResultFile($file);
            $this->hide();
        });

        $dialog = DialogSystem::getImage();
        $this->dialog = $dialog;

        $imageArea = new UXImageArea();
        $imageArea->centered = true;
        $imageArea->proportional = true;
        $imageArea->stretch = false;
        $imageArea->smartStretch = true;
        UXAnchorPane::setAnchor($imageArea, 0);

        $this->imageView->add($imageArea);
        $this->imageArea = $imageArea;

        Ide::accountManager()->on('login', [$this, 'updateOnlineGallery'], __CLASS__);
        Ide::accountManager()->on('logout', [$this, 'updateOnlineGallery'], __CLASS__);
    }

    public function updateOnlineGallery()
    {
        /*$this->onlineSearchPane->children->clear();

        if (!Ide::accountManager()->isAuthorized()) {
            $this->onlineSearchPane->add(new NeedAuthPane());
        } else {
            $this->onlineSearchPane->add($this->searchPaneArea);
        } */
    }

    public function updateGallery($searchQuery = '')
    {
        $this->projectGallery->clear();

        $project = Ide::get()->getOpenedProject();
        $searchQuery = str::lower($searchQuery);

        if ($project) {
            Ide::async(function () use ($project, $searchQuery) {
                fs::scan($project->getFile('src/.data'), function ($filename) use ($project, $searchQuery) {
                    $file = $project->getAbsoluteFile($filename);

                    if (!$file->isFile()) {
                        return;
                    }

                    $ext = str::lower(fs::ext($filename));

                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $name = str::lower(fs::name($filename));

                        if ($searchQuery && !str::contains($name, $searchQuery)) {
                            return;
                        }

                        uiLater(function () use ($filename, $file) {
                            $one = new ImageBox(72, 72);
                            $one->setImage(Cache::getImage($filename));
                            $one->setTitle($file->getName());
                            $one->setTooltip($file->getPath());
                            $one->data('file', $filename);

                            $one->on('click', function (UXMouseEvent $e) use ($file) {
                                $this->setResult($file->getRelativePath('src'));

                                if ($e->clickCount >= 2) {
                                    $this->hide();
                                }
                            });

                            $this->projectGallery->add($one);
                        });
                    }
                });
            });
        }
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
                $image = Cache::getImage($file);
                $this->imageArea->image = $image;
            } else {
                $image = Ide::get()->getImage('dummyImage.png')->image;
                $this->imageArea->image = $image;
            }

            $this->pathField->text = $path;
        });
    }

    /**
     * @param absolute $file
     */
    public function setResultFile($file)
    {
        $files = Ide::get()->getOpenedProject()->findDuplicatedFiles($file);

        if ($files) {
            $file = Items::first($files);
            $this->setResult($file->getRelativePath('src/'));
        } else {
            $project = Ide::get()->getOpenedProject();

            if ($project) {
                $file = $project->copyFile($file, 'src/.data/img/');
                $this->setResult($file->getRelativePath('src/'));
            }
        }
    }

    /**
     * @event addToGalleryButton.action
     */
    public function actionAddToGallery()
    {
        if ($files = $this->dialog->showOpenMultipleDialog()) {
            $project = Ide::get()->getOpenedProject();

            $showed = false;

            foreach ($files as $file) {
                if ($project->findDuplicatedFiles($file)) {
                    if (!$showed) {
                        $this->toast('Изображение уже есть в проекте');
                        $showed = true;
                    }
                } else {
                    $file = $project->copyFile($file, 'src/.data/img/');
                    $this->setResult($file->getRelativePath('src/'));
                }
            }

            $this->updateGallery();
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
     * @event projectQueryField.keyUp
     * @event projectSearchButton.action
     */
    public function doSearchInProject()
    {
        $this->updateGallery($this->projectQueryField->text);
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
        $this->updateGallery($this->projectQueryField->text);
        $this->updateOnlineGallery();
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

            if (fs::isFile($file)) {
                $this->setResult($path);
            } elseif (fs::isFile($path)) {
                $files = $project->findDuplicatedFiles($path);

                if ($files) {
                    $file = arr::first($files);
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