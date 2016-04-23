<?php
namespace ide\forms;

use ide\forms\mixins\DialogFormMixin;
use ide\forms\mixins\SavableFormMixin;
use ide\misc\EventHandlerBehaviour;
use php\gui\UXCheckbox;
use php\gui\UXImageView;
use php\gui\UXTextField;

/**
 * Class FindTextDialogForm
 * @package ide\forms
 *
 * @property UXImageView $icon
 * @property UXTextField $inputField
 * @property UXCheckbox $wholeTextCheckbox
 * @property UXCheckbox $caseCheckbox
 */
class FindTextDialogForm extends AbstractIdeForm
{
    use DialogFormMixin;
    use SavableFormMixin;

    /**
     * @var callable
     */
    protected $onSearch = null;

    /**
     * FindTextDialogForm constructor.
     * @param callable $onSearch
     */
    public function __construct(callable $onSearch)
    {
        parent::__construct();

        $this->onSearch = $onSearch;
    }

    protected function init()
    {
        parent::init();

        $this->observer('focused')->addListener(function ($old, $new) {
            if (!$new) {
                $this->hide();
            }
        });

        $this->icon->image = ico('search32')->image;
    }

    /**
     * @event show
     */
    public function doShow()
    {
        $this->inputField->text = $this->getResult();
    }

    /**
     * @event inputField.keyDown-Enter
     * @event searchButton.action
     */
    public function doSearch()
    {
        $onSearch = $this->onSearch;

        $options = [
            'case' => $this->caseCheckbox->selected,
            'wholeText' => $this->wholeTextCheckbox->selected
        ];

        $onSearch($this->inputField->text, $options);
    }

    /**
     * @event cancelButton.action
     */
    public function doCancel()
    {
        $this->hide();
    }
}