<?php
namespace ide\forms;

use ide\forms\mixins\DialogFormMixin;
use ide\forms\mixins\SavableFormMixin;
use ide\misc\EventHandlerBehaviour;
use php\gui\UXButton;
use php\gui\UXCheckbox;
use php\gui\UXImageView;
use php\gui\UXTextField;

/**
 * @package ide\forms
 *
 * @property UXImageView $icon
 * @property UXTextField $inputField
 * @property UXTextField $replaceField
 * @property UXCheckbox $wholeTextCheckbox
 * @property UXCheckbox $caseCheckbox
 * @property UXButton $skipButton
 */
class ReplaceTextDialogForm extends AbstractIdeForm
{
    use DialogFormMixin;
    use SavableFormMixin;

    /**
     * @var callable
     */
    protected $onSearch = null;

    /**
     * @param callable $onSearch
     */
    public function __construct(callable $onSearch)
    {
        parent::__construct();

        $this->onSearch = $onSearch;
    }

    /**
     * @param array|null $result
     */
    public function setFindResult($result)
    {
        $this->skipButton->enabled = !!$result;
    }

    protected function init()
    {
        parent::init();

        $this->observer('focused')->addListener(function ($old, $new) {
            if (!$new) {
                $this->hide();
            }
        });

        $this->icon->image = ico('replace32')->image;
    }

    /**
     * @event show
     */
    public function doShow()
    {
        $this->setFindResult(null);
        $this->inputField->text = $this->getResult();
    }

    public function getOptions()
    {
        $options = [
            'case' => $this->caseCheckbox->selected,
            'wholeText' => $this->wholeTextCheckbox->selected
        ];

        return $options;
    }

    /**
     * @event inputField.keyUp
     */
    public function doStart()
    {
        $onSearch = $this->onSearch;
        $onSearch($this->inputField->text, $this->replaceField->text, $this->getOptions(), 'START');
    }

    /**
     * @event replaceButton.action
     */
    public function doReplace()
    {
        $onSearch = $this->onSearch;
        $onSearch($this->inputField->text, $this->replaceField->text, $this->getOptions(), 'REPLACE');
    }

    /**
     * @event skipButton.action
     */
    public function doSkip()
    {
        $onSearch = $this->onSearch;
        $onSearch($this->inputField->text, $this->replaceField->text, $this->getOptions(), 'SKIP');
    }

    /**
     * @event cancelButton.action
     */
    public function doCancel()
    {
        $this->hide();
    }
}