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
use php\xml\DomElement;

class ImagePropertyEditor extends TextPropertyEditor
{
    /**
     * @var bool
     */
    private $nativeImage;

    /**
     * @var bool
     */
    private $dummyValue;

    public function __construct($nativeImage = false, $dummyValue = false, callable $getter = null, callable $setter = null)
    {
        parent::__construct($getter, $setter);

        $this->nativeImage = $nativeImage;
        $this->dummyValue = $dummyValue;
    }


    public function makeUi()
    {
        $result = parent::makeUi();

        $this->textField->editable = false;

        $this->dialogButton->on('click', function (UXMouseEvent $e) {
            /** @var ImagePropertyEditorForm $dialog */
            $dialog = Ide::get()->getForm('ImagePropertyEditorForm');
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

        $target = $this->designProperties->target;

        if ($value) {
            $file = $project->getFile("src/$value");

            if ($file->exists()) {
                if (!$this->nativeImage) {
                    $icon = new UXImageView();
                    $icon->image = new UXImage($file);
                    $target->{$this->code} = $icon;
                } else {
                    $img = new UXImage($file);
                    $target->{$this->code} = $img;
                }
                return;
            }
        }

        if ($this->dummyValue) {
            $target->{$this->code} = Ide::get()->getImage('dummyImage.png')->image;
        } else {
            $target->{$this->code} = null;
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

    /**
     * @param DomElement $element
     *
     * @return ElementPropertyEditor
     */
    public function unserialize(DomElement $element)
    {
        $editor = new static($element->getAttribute('nativeImage'), $element->getAttribute('dummyValue'));
        return $editor;
    }
}