<?php
namespace ide\forms;


use ide\forms\mixins\DialogFormMixin;
use ide\library\IdeLibraryResource;
use ide\project\Project;
use php\gui\framework\AbstractForm;
use php\gui\UXImageView;
use php\gui\UXTextArea;
use php\gui\UXTextField;

/**
 * Class SaveProjectForLibraryForm
 * @package ide\forms
 *
 * @property UXTextField $nameField
 * @property UXTextArea $descriptionField
 * @property UXImageView $icon
 */
class SaveProjectForLibraryForm extends AbstractIdeForm
{
    use DialogFormMixin;


    /**
     * SaveProjectForLibraryForm constructor.
     * @param IdeLibraryResource $resource
     */
    public function __construct(IdeLibraryResource $resource = null)
    {
        parent::__construct();

        if ($resource) {
            $this->nameField->text = $resource->getName();
            $this->descriptionField->text = $resource->getDescription();
        }
    }

    protected function init()
    {
        parent::init();

        $this->icon->image = ico('library32')->image;
    }


    /**
     * @event show
     */
    public function doShow()
    {
        /** @var Project $result */
        $result = $this->getResult();

        if (!$this->nameField->text) {
            $this->nameField->text = $result->getName();
        }
    }

    /**
     * @event close
     * @event cancelButton.action
     */
    public function doCancel()
    {
        $this->setResult(null);
        $this->hide();
    }

    /**
     * @event createButton.action
     */
    public function doSave()
    {
        $this->setResult([
            'name' => $this->nameField->text,
            'description' => $this->descriptionField->text,
        ]);
        $this->hide();
    }
}