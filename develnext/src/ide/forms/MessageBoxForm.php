<?php
namespace ide\forms;

use ide\forms\mixins\DialogFormMixin;
use ide\Ide;
use ide\utils\UiUtils;
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
use php\gui\UXWindow;
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
     * @var mixed
     */
    protected $iconImage;

    /**
     * @param string $text
     * @param array $buttons
     * @param null $owner
     */
    public function __construct($text, array $buttons, $owner = null)
    {
        parent::__construct();

        $this->text = $text;
        $this->buttons = $buttons;
        $this->owner = $owner instanceof UXNode ? $owner->form : ($owner instanceof UXWindow ? $owner : $this->owner);
        $this->iconImage = 'icons/question32.png';
    }

    public function makeWarning()
    {
        $this->iconImage = 'icons/warning32.png';
    }

    protected function init()
    {
        parent::init();

        $this->title = _('msg.title');
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

    public function showWarningDialog($x = null, $y = null)
    {
        $this->makeWarning();
        return $this->showDialog($x, $y);
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
        $image = Ide::get()->getImage($this->iconImage);
        $this->icon->image = $image ? $image->image : null;

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

            $ui->style .= ";" . UiUtils::fontSizeStyle();

            $this->buttonBox->add($ui);
        }

        $this->layout->requestLayout();
        $this->centerOnScreen();
    }

    static function confirm($message, $owner = null)
    {
        $dialog = new static($message, ['Да', 'Нет, отмена'], $owner);

        return $dialog->showDialog() && $dialog->getResultIndex() == 0;
    }

    static function confirmDelete($what, $owner = null)
    {
        if (is_array($what)) {
            $what = str::join($what, ", ");
        }

        $dialog = new static("Вы уверены, что хотите удалить '$what'?", ['Да, удалить', 'Нет'], $owner);

        return $dialog->showDialog() && $dialog->getResultIndex() == 0;
    }

    static function confirmExit($owner = null)
    {
        $dialog = new static("Вы уверены, что хотите выйти?", ['Да, выйти', 'Нет'], $owner);

        return $dialog->showDialog() && $dialog->getResultIndex() == 0;
    }

    static function warning($message, $owner = null)
    {
        $dialog = new static($message, ['OK'], $owner);
        return $dialog->showWarningDialog();
    }
}