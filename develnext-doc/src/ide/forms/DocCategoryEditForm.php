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
     * @package ide\forms
     *
     * @property UXTextField $nameField
     * @property UXTextArea $descriptionField
     * @property UXTextField $weightField
     */
    class DocCategoryEditForm extends AbstractIdeForm
    {
        use DialogFormMixin;

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
            $this->result['description'] = $this->descriptionField->text;
            $this->result['weight'] = $this->weightField->text;

            $this->events->trigger('save', [$this->result]);
        }

        public function updateUi()
        {
            $this->nameField->text = $this->result['name'];
            $this->descriptionField->text = $this->result['description'];
            $this->weightField->text = $this->result['weight'];
        }
    }
}