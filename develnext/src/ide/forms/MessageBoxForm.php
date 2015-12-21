<?php
namespace ide\forms;

use ide\forms\mixins\DialogFormMixin;
use ide\Ide;
use php\gui\framework\AbstractForm;
use php\gui\layout\UXHBox;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXCheckbox;
use php\gui\UXControl;
use php\gui\UXForm;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\gui\UXNode;

/**
 * @property UXHBox $buttonBox
 * @property UXLabel $messageLabel
 * @property UXImageView $icon
 *
 * @property UXCheckbox $flag
 *
 * Class MessageBoxForm
 * @package ide\forms
 */
class MessageBoxForm extends AbstractIdeForm
{
    use DialogFormMixin;

    /** @var string */
    protected $text;

    /** @var array */
    protected $buttons = [];

    /** @var int */
    protected $indexResult = -1;

    /**
     * @param string $text
     * @param array $buttons
     */
    public function __construct($text, array $buttons)
    {
        parent::__construct();

        $this->text = $text;
        $this->buttons = $buttons;
    }

    public function isChecked()
    {
        return $this->flag->selected;
    }

    public function showDialogWithFlag()
    {
        UXApplication::runLater(function() {
            $this->flag->visible = true;
            $this->height += 42;
        });

        return $this->showDialog();
    }

    /**
     * @return int
     */
    public function getResultIndex()
    {
        return $this->indexResult;
    }

    /**
     * @event show
     */
    public function doOpen()
    {
        $this->indexResult = -1;
        $this->icon->image = Ide::get()->getImage('icons/question32.png')->image;

        $this->iconified = false;
        $this->messageLabel->text = $this->text;

        $i = 0;
        foreach ($this->buttons as $value => $button)
        {
            if ($button instanceof UXNode) {
                $this->buttonBox->add($button);
                continue;
            }

            $ui = new UXButton($button);
            $ui->minWidth = 100;
            $ui->maxHeight = 10000;

            $ui->on('action', function() use ($value, $i) {
                $this->setResult($value);
                $this->indexResult = $i;
                $this->hide();
            });

            if ($i++ == 0) {
                $ui->style = '-fx-font-weight: bold';
            }

            $this->buttonBox->add($ui);
        }
    }
}