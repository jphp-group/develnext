<?php
namespace ide\forms;

use ide\forms\mixins\DialogFormMixin;
use ide\utils\UiUtils;
use InvalidArgumentException;
use php\gui\framework\AbstractForm;
use php\gui\text\UXFont;
use php\gui\UXButton;
use php\gui\UXComboBox;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXSlider;
use php\gui\UXTextField;
use php\gui\UXToggleButton;
use php\lib\str;
use php\util\Flow;

/**
 * Class FontPropertyEditorForm
 * @package ide\forms
 *
 * @property UXComboBox $fontCombobox
 * @property UXSlider $sizeSlider
 * @property UXTextField $sizeField
 * @property UXButton $genCssButton
 * @property UXComboBox $customFontCombobox
 * @property UXButton $addFontButton
 * @property UXLabel $testLabel
 *
 * @property UXToggleButton $thinWeightButton
 * @property UXToggleButton $italicBoldWeightButton
 * @property UXToggleButton $italicButton
 * @property UXToggleButton $boldWeightButton
 */
class FontPropertyEditorForm extends AbstractIdeForm
{
    use DialogFormMixin;

    private $freeze = false;

    protected function init()
    {
        $this->watch('focused', function ($self, $prop, $old, $new) {
            if (!$new && !$this->freeze) {
                $this->hide();
            }
        });

        $this->fontCombobox->items->add('System');

        foreach (UXFont::getFamilies() as $family) {
            $this->fontCombobox->items->add($family);
        }

        UiUtils::makeAutoCompleteComboBox($this->fontCombobox, function ($one, $text) {
            return str::startsWith(str::lower($one), str::trim(str::lower($text)));
        });

        $this->fontCombobox->onCellRender(function (UXListCell $cell, $value) {
            try {
                $cell->font = UXFont::of($value, $cell->font->size);
            } catch (InvalidArgumentException $e) {
                $cell->font = UXFont::of('System', $cell->font->size);
            }
            $cell->text = $value;
        });

        $lock = false;

        $this->sizeSlider->observer('value')->addListener(function ($old, $new) use (&$lock) {
            if (!$lock) {
                $this->sizeField->text = (int) $new;
                $this->updateTestText();
            }
        });

        $this->sizeField->on('keyUp', function () use (&$lock) {
            $lock = true;
            $this->sizeSlider->value = $this->sizeField->text;
            $this->updateTestText();
            $lock = false;
        });

        $handle = function () {
            $this->updateTestText();
        };

        $this->boldWeightButton->on('action', $handle);
        $this->italicBoldWeightButton->on('action', $handle);
        $this->italicButton->on('action', $handle);
        $this->thinWeightButton->on('action', $handle);
        $this->fontCombobox->on('action', $handle);
        $this->fontCombobox->on('keyUp', $handle);
    }

    protected function updateTestText()
    {
        uiLater(function () {
            $font = $this->getFont();
            $this->testLabel->font = $font;
        });
    }

    /**
     * @event show
     */
    public function actionOpen()
    {
        $font = $this->getResult();

        if ($font instanceof UXFont) {
            $this->fontCombobox->value = $font->family;
            $this->sizeSlider->value = $this->sizeField->text = (int) $font->size;

            switch ($font->style) {
                case 'Bold':
                    $this->boldWeightButton->selected = true;
                    break;
                case 'Bold Italic':
                    $this->italicBoldWeightButton->selected = true;
                    break;
                case 'Italic':
                    $this->italicButton->selected = true;
                    break;
                case 'Regular':
                default:
                    $this->thinWeightButton->selected = true;
                    break;
            }
        }
    }

    public function getFont()
    {
        $weight = 'THIN';

        if ($this->boldWeightButton->selected || $this->italicBoldWeightButton->selected) {
            $weight = 'BOLD';
        }

        $italic = ($this->italicButton->selected || $this->italicBoldWeightButton->selected);

        return UXFont::of($this->fontCombobox->value, $this->sizeField->text, $weight, $italic);
    }

    /**
     * @event genCssButton.action
     */
    public function actionGenStyle()
    {
        $this->freeze = true;
        $dialog = new ShowTextDialogForm('Шрифт в виде css:', $this->getFont()->generateStyle(), true);
        $dialog->showDialog();
        $this->freeze = false;
    }

    /**
     * @event applyButton.action
     */
    public function actionApply()
    {
        $this->setResult($this->getFont());
        $this->hide();
    }

    /**
     * @event cancelButton.action
     */
    public function actionCancel()
    {
        $this->setResult(null);
        $this->hide();
    }
}