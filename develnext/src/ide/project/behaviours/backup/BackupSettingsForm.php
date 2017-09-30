<?php
namespace ide\project\behaviours\backup;

use ide\forms\AbstractIdeForm;
use ide\forms\mixins\DialogFormMixin;
use ide\forms\mixins\SavableFormMixin;
use ide\Ide;
use php\gui\event\UXMouseEvent;
use php\gui\UXCheckbox;
use php\gui\UXImageView;
use php\gui\UXSlider;

/**
 * Class BackupSettingsForm
 * @package ide\project\behaviours\backup
 *
 * @property UXCheckbox $autoCheckbox
 * @property UXCheckbox $autoCloseCheckbox
 * @property UXSlider $autoIntervalSlider
 * @property UXSlider $autoMaxSlider
 * @property UXSlider $autoSessionMaxSlider
 * @property UXImageView $icon
 */
class BackupSettingsForm extends AbstractIdeForm
{
    use DialogFormMixin;
    use SavableFormMixin;

    protected function init()
    {
        parent::init();

        $this->icon->image = ico('backup32')->image;
    }

    /**
     * @event showing
     */
    public function doShowing()
    {
        $result = (array) $this->getResult();

        $this->autoCheckbox->selected = $result['autoIntervalTrigger'];
        $this->autoIntervalSlider->value = round($result['autoIntervalTriggerTime'] / 1000 / 60);
        $this->autoMaxSlider->value = $result['autoAmountMax'];
        $this->autoSessionMaxSlider->value = $result['autoAmountMaxInSession'];
        $this->autoCloseCheckbox->selected = $result['autoCloseTrigger'];

        $this->autoIntervalSlider->enabled = $this->autoCheckbox->enabled;
    }

    /**
     * @event autoCheckbox.click
     */
    public function doAutoClick()
    {
        $this->autoIntervalSlider->enabled = $this->autoCheckbox->enabled;
    }

    /**
     * @event autoIntervalSlider.mouseDrag
     * @event autoMaxSlider.mouseDrag
     * @event autoSessionMaxSlider.mouseDrag
     *
     * @param UXMouseEvent $e
     */
    public function doSliderFixValue(UXMouseEvent $e)
    {
        uiLater(function () use ($e) {
           $e->sender->value = round($e->sender->value);
        });
    }

    /**
     * @event saveButton.action
     */
    public function doSave()
    {
        $this->setResult([
            'autoIntervalTrigger' => $this->autoCheckbox->selected,
            'autoIntervalTriggerTime' => $this->autoIntervalSlider->value * 1000 * 60,
            'autoAmountMax' => $this->autoMaxSlider->value,
            'autoAmountMaxInSession' => $this->autoSessionMaxSlider->value,
            'autoCloseTrigger' => $this->autoCloseCheckbox->selected
        ]);

        $this->hide();
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
}