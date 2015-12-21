<?php
namespace ide\forms;

use ide\forms\mixins\DialogFormMixin;
use php\gui\framework\AbstractForm;
use php\gui\text\UXFont;
use php\gui\UXButton;
use php\gui\UXComboBox;
use php\gui\UXListCell;
use php\gui\UXToggleButton;
use php\util\Flow;

/**
 * Class FontPropertyEditorForm
 * @package ide\forms
 *
 * @property UXComboBox $fontCombobox
 * @property UXComboBox $fontSizeCombobox
 * @property UXComboBox $customFontCombobox
 * @property UXButton $addFontButton
 *
 * @property UXToggleButton $thinWeightButton
 * @property UXToggleButton $italicBoldWeightButton
 * @property UXToggleButton $italicButton
 * @property UXToggleButton $boldWeightButton
 */
class FontPropertyEditorForm extends AbstractIdeForm
{
    use DialogFormMixin;

    protected function init()
    {
        $this->watch('focused', function ($self, $prop, $old, $new) {
            if (!$new) {
                $this->hide();
            }
        });

        $this->fontCombobox->items->add('System');

        foreach (UXFont::getFamilies() as $family) {
            $this->fontCombobox->items->add($family);
        }

        foreach (Flow::ofRange(6, 30) as $size) {
            $this->fontSizeCombobox->items->add($size);
        }

        $this->fontCombobox->onCellRender(function (UXListCell $cell, $value) {
            $cell->font = UXFont::of($value, $cell->font->size);
            $cell->text = $value;
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
            $this->fontSizeCombobox->text = (int) $font->size;

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

    /**
     * @event applyButton.action
     */
    public function actionApply()
    {
        $weight = 'THIN';

        if ($this->boldWeightButton->selected || $this->italicBoldWeightButton->selected) {
            $weight = 'BOLD';
        }

        $italic = ($this->italicButton->selected || $this->italicBoldWeightButton->selected);

        $result = UXFont::of($this->fontCombobox->value, $this->fontSizeCombobox->text, $weight, $italic);
        $this->setResult($result);
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