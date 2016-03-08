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
     * @property UXComboBox $capabilitySelect
     * @property UXAnchorPane $contentArea
     * @property UXTextArea $contentField
     * @property UXTextField $weightField
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
            $this->capabilitySelect->items->addAll([
                '',
                '2016, V-4',
                '2016, V-3',
                '2016, V-2',
                '2016, V-1',
                '2016, RC-2, patch-b',
                '2016, RC-2, patch-a',
                '2016, RC-2',
                '2016, RC-1, patch-b',
                '2016, RC-1, patch-a',
                '2016, RC-1',
                '2016, Beta-4, patch-b',
                '2016, Beta-4, patch-a',
                '2016, Beta-4',
                '2016, Beta-3, patch-b',
                '2016, Beta-3, patch-a',
                '2016, Beta-3',
                '2016, Beta-2, patch-b',
                '2016, Beta-2, patch-a',
                '2016, Beta-2',
                '2016, Beta-1, patch-b',
                '2016, Beta-1, patch-a',
                '2016, Beta-1',
                '2016, Alpha-5, patch-b',
                '2016, Alpha-5, patch-a',
                '2016, Alpha-5',
                '2016, Alpha-4, patch-b',
                '2016, Alpha-4, patch-a',
                '2016, Alpha-3',
            ]);

            $this->capabilitySelect->selectedIndex = 0;
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
            $this->result['weight'] = $this->weightField->text;
            $this->result['capability'] = $this->capabilitySelect->value;

            $this->events->trigger('save', [$this->result]);
        }

        public function updateUi()
        {
            $this->codeField->text = $this->result['code'];
            $this->nameField->text = $this->result['name'];
            $this->descriptionField->text = $this->result['description'];
            $this->contentField->text = $this->result['content'];
            $this->weightField->text = $this->result['weight'];
            $this->capabilitySelect->value = $this->result['capability'];
        }
    }
}