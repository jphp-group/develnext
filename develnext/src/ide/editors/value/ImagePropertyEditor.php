<?php
namespace ide\editors\value;

use ide\forms\FontPropertyEditorForm;
use ide\forms\ImagePropertyEditorForm;
use ide\Ide;
use php\gui\event\UXMouseEvent;
use php\gui\text\UXFont;
use php\gui\UXImage;
use php\gui\UXImageArea;
use php\gui\UXImageView;
use php\lib\String;

class ImagePropertyEditor extends TextPropertyEditor
{
    public function makeUi()
    {
        $result = parent::makeUi();

        $this->textField->editable = false;

        $this->dialogButton->on('click', function (UXMouseEvent $e) {
            $dialog = new ImagePropertyEditorForm();
            $dialog->title = $this->name;

            $dialog->setResult($this->getNormalizedValue($this->getValue()));

            if ($dialog->showDialog()) {
                $this->applyValue($dialog->getResult());
            }
        });

        return $result;
    }

    public function updateUi($value)
    {
        parent::updateUi($value);

        $project = Ide::get()->getOpenedProject();

        if ($value) {
            $file = $project->getFile("src/$value");

            if ($file->exists()) {
                if (!($this->designProperties->target instanceof UXImageArea)) {
                    $icon = new UXImageView();
                    $icon->image = new UXImage($file);
                    $this->designProperties->target->{$this->code} = $icon;
                } else {
                    $img = new UXImage($file);
                    $this->designProperties->target->{$this->code} = $img;
                }
                return;
            }
        }

        if ($this->designProperties->target instanceof UXImageArea && $this->code == 'image') {
            $this->designProperties->target->{$this->code} = Ide::get()->getImage('dummyImage.png')->image;
        } else {
            $this->designProperties->target->{$this->code} = null;
        }
    }

    public function getNormalizedValue($value)
    {
        return $value;
    }

    public function getCode()
    {
        return 'image';
    }
}