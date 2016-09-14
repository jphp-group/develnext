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
use php\lib\str;

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
    use DialogFormMixin {
        showDialog as private _showDialog;
    }

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

    protected function init()
    {
        parent::init();

        $this->owner = Ide::get()->getMainForm();
    }

    public function isChecked()
    {
        return $this->flag->selected;
    }

    public function showDialogWithFlag()
    {
        UXApplication::runLater(function () {
            $this->centerOnScreen();
        });
        return $this->_showDialog();
    }

    public function showDialog($x = null, $y = null)
    {
        $this->flag->free();
        UXApplication::runLater(function () {
            $this->centerOnScreen();
        });
        return $this->_showDialog($x, $y);
    }

    /**
     * @return int
     */
    public function getResultIndex()
    {
        return $this->indexResult;
    }

    /**
     * @event showing
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
            $ui->maxHeight = 10000;
            $ui->minWidth = 90;
            $ui->height = 30;
            $ui->paddingLeft = $ui->paddingRight = 15;

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

        $this->layout->requestLayout();
        $this->centerOnScreen();
    }

    static function confirm($message)
    {
        $dialog = new static($message, ['Да', 'Нет, отмена']);

        return $dialog->showDialog() && $dialog->getResultIndex() == 0;
    }

    static function confirmDelete($what)
    {
        if (is_array($what)) {
            $what = str::join($what, ", ");
        }

        $dialog = new static("Вы уверены, что хотите удалить '$what'?", ['Да, удалить', 'Нет']);

        return $dialog->showDialog() && $dialog->getResultIndex() == 0;
    }


    static function confirmExit()
    {
        $dialog = new static("Вы уверены, что хотите выйти?", ['Да, выйти', 'Нет']);
        return $dialog->showDialog() && $dialog->getResultIndex() == 0;
    }
}