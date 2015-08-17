<?php
namespace ide\forms;

use Files;
use ide\forms\mixins\DialogFormMixin;
use ide\Ide;
use ide\utils\FileUtils;
use php\gui\framework\AbstractForm;
use php\gui\UXApplication;
use php\gui\UXFileChooser;
use php\gui\UXImage;
use php\util\Flow;

class ImagePropertyEditorForm extends AbstractForm
{
    use DialogFormMixin {
        DialogFormMixin::setResult as protected _setResult;
    }

    protected $copiedFile = null;

    public function setResult($path)
    {
        $this->result = $path;

        if ($this->copiedFile) {
            $this->copiedFile->delete();
            $this->copiedFile = null;
        }

        UXApplication::runLater(function () use ($path) {
            $file = $path ? Ide::get()->getOpenedProject()->getFile("src/$path") : null;

            if (Files::isFile($file)) {
                $image = new UXImage($file);
                $this->image->image = $image;
            } else {
                $image = Ide::get()->getImage('dummyImage.png')->image;
                $this->image->image = $image;
            }

            UXApplication::runLater(function () use ($image) {
                $size = [475, 415];

                if ($image->width < $size[0] && $image->height < $size[1]) {
                    $this->image->size = [$image->width, $image->height];
                    $this->image->position = [$size[0] / 2 - $image->width / 2, $size[1] / 2 - $image->height / 2];
                } else {
                    $this->image->position = [0, 0];
                    $this->image->size = $size;
                }
            });

            $this->pathField->text = $path;
        });
    }

    /**
     * @event loadButton.action
     */
    public function actionLoad()
    {
        $dialog = new UXFileChooser();
        $dialog->extensionFilters = [
            ['description' => 'Изображения (jpg, png, gif)', 'extensions' => ['*.jpg', '*.jpeg', '*.png', '*.gif']]
        ];

        $project = Ide::get()->getOpenedProject();

        if ($file = $dialog->execute()) {
            $file = $project->copyFile($file, 'src/.data/img/');
            $this->setResult($file->getRelativePath('src'));
            $this->copiedFile = $file;
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
     * @event applyButton.action
     */
    public function actionApply()
    {
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