<?php
namespace ide\forms;
use ide\forms\mixins\DialogFormMixin;
use php\gui\UXLabel;
use php\gui\UXTextField;
use php\gui\UXTooltip;
use php\util\Regex;

/**
 * Class InputMessageBoxForm
 * @package ide\forms
 *
 * @property UXLabel $titleLabel
 * @property UXLabel $qLabel
 * @property UXLabel $helpLabel
 * @property UXTextField $edit
 */
class InputMessageBoxForm extends AbstractIdeForm
{
    use DialogFormMixin;

    /**
     * @var Regex
     */
    protected $pattern;

    /**
     * @var string
     */
    protected $patternErrorText;

    /**
     * @param string $title
     * @param string $question
     * @param string $helpText
     */
    public function __construct($title, $question, $helpText = '')
    {
        parent::__construct();

        $this->title = $title;
        $this->titleLabel->text = $title;
        $this->qLabel->text = $question;
        $this->helpLabel->text = $helpText;
    }

    /**
     * @param Regex $pattern
     * @param $errorText
     */
    public function setPattern(Regex $pattern, $errorText)
    {
        $this->pattern = $pattern;
        $this->patternErrorText = $errorText;
    }

    /**
     * @event edit.keyUp-Enter
     * @event saveButton.action
     */
    public function doSave()
    {
        if ($this->pattern) {
            if (!$this->pattern->test($this->edit->text)) {
                $tooltip = new UXTooltip();
                $tooltip->classes->add('dn-tooltip-error');
                $tooltip->text = $this->patternErrorText;

                $tooltip->showByNode($this->edit, 0, $this->edit->height + 2);

                waitAsync(3000, function () use ($tooltip) { $tooltip->hide(); });
                return;
            }
        }

        $this->setResult($this->edit->text);
        $this->hide();
    }

    /**
     * @event cancelButton.action
     */
    public function doCancel()
    {
        $this->setResult(null);
        $this->hide();
    }
}