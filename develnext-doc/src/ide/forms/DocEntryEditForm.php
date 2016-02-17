<?php
namespace ide\forms {

    use ide\editors\common\CodeTextArea;
    use ide\forms\mixins\DialogFormMixin;
    use ide\forms\mixins\SavableFormMixin;
    use ide\misc\EventHandler;
    use ide\misc\EventHandlerBehaviour;
    use php\gui\layout\UXAnchorPane;
    use php\gui\UXComboBox;
    use php\gui\UXTextArea;
    use php\gui\UXTextField;

    /**
     * Class DocEntryEditForm
     * @package ide\forms
     *
     * @property UXTextField $nameField
     * @property UXTextArea $descriptionField
     * @property UXTextField $codeField
     * @property UXComboBox $categorySelect
     * @property UXAnchorPane $contentArea
     * @property UXTextArea $contentField
     */
    class DocEntryEditForm extends AbstractIdeForm
    {
        use DialogFormMixin;
        use SavableFormMixin;

        /**
         * @var EventHandler
         */
        public $events;

        protected function init()
        {
            parent::init();

            $this->events = new EventHandler();
        }

        /**
         * @event show
         */
        public function doShow()
        {
            $this->updateUi();
        }

        /**
         * @event saveButton.action
         */
        public function doSave()
        {
            $this->doApply();
            $this->hide();
        }

        /**
         * @event applyButton.action
         */
        public function doApply()
        {
            $this->result['name'] = $this->nameField->text;
            $this->result['code'] = $this->codeField->text;
            $this->result['description'] = $this->descriptionField->text;
            $this->result['content'] = $this->contentField->text;

            $this->events->trigger('save', [$this->result]);
        }

        public function updateUi()
        {
            $this->codeField->text = $this->result['code'];
            $this->nameField->text = $this->result['name'];
            $this->descriptionField->text = $this->result['description'];
            $this->contentField->text = $this->result['content'];
        }
    }
}