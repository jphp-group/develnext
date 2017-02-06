<?php
namespace ide\forms;
use ide\editors\CodeEditor;
use ide\forms\mixins\DialogFormMixin;
use ide\forms\mixins\SavableFormMixin;
use ide\Ide;
use php\gui\UXComboBox;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXSlider;
use php\lang\IllegalArgumentException;
use php\lib\arr;
use php\lib\fs;

/**
 * Class CodeEditorSettingsForm
 * @package ide\forms
 *
 * @property UXImageView $icon
 * @property UXSlider $fontSizeSlider
 * @property UXComboBox $themeList
 * @property UXLabel $titleLabel
 * @property UXComboBox $importType
 */
class CodeEditorSettingsForm extends AbstractIdeForm
{
    use DialogFormMixin;
    use SavableFormMixin;

    /**
     * @var string
     */
    protected $lang;

    /**
     * @var CodeEditor
     */
    protected $editor;

    protected function init()
    {
        parent::init();

        $this->icon->image = ico('settings32')->image;

        $this->themeList->onButtonRender(function (UXListCell $cell, $value) {
            $cell->text = $value;
            $cell->font = $cell->font->withBold();
        });

        $this->themeList->on('action', function () {
            if ($this->editor) {
                $this->editor->setHighlight($this->themeList->selected);
            }
        });

        $v = function () {
            if ($this->editor) {
                $this->editor->setFontSize($this->fontSizeSlider->value);
            }
        };

        $this->fontSizeSlider->on('mouseUp', $v);
        $this->fontSizeSlider->on('mouseDrag', $v);
    }

    /**
     * @event showing
     */
    public function doShowing()
    {
        if (!$this->lang) {
            throw new IllegalArgumentException("Lang is not set");
        }

        $files = CodeEditor::getHighlightFiles($this->lang);

        $this->themeList->items->clear();
        //$this->themeList->style = '-fx-font-weight: bold;';

        foreach ($files as $file) {
            $this->themeList->items->add(fs::nameNoExt($file));
        }

        $this->themeList->selected = CodeEditor::getCurrentHighlight($this->lang);
        $this->fontSizeSlider->value = CodeEditor::getCurrentFontSize($this->lang);
        $this->titleLabel->text = 'Настройки редактора ' . $this->lang;
    }

    /**
     * @event saveButton.action
     */
    public function doSave()
    {
        CodeEditor::setCurrentHighlight($this->lang, $this->themeList->selected);
        CodeEditor::setCurrentFontSize($this->lang, $this->fontSizeSlider->value);

        $this->hide();
    }

    /**
     * @event close
     * @event cancelButton.action
     */
    public function doCancel()
    {
        if ($this->editor) {
            $this->editor->resetSettings();
        }

        $this->hide();
    }

    /**
     * @return CodeEditor
     */
    public function getEditor()
    {
        return $this->editor;
    }

    /**
     * @param CodeEditor $editor
     */
    public function setEditor($editor)
    {
        $this->editor = $editor;
        $this->setLang($editor->getMode());
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param string $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }
}