<?php
namespace ide\forms;

use ide\Ide;
use ide\ui\Notifications;
use php\gui\event\UXEvent;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXDesktop;
use php\gui\UXLabel;
use php\gui\UXTextArea;

/**
 * Class UpdateAvailableForm
 * @package ide\forms
 *
 * @property UXTextArea $descriptionField
 * @property UXLabel $nameLabel
 *
 * @property UXButton $youtubeButton
 * @property UXButton $downloadButton
 */
class UpdateAvailableForm extends AbstractIdeForm
{
    /**
     * @var string
     */
    protected $videoLink;

    /**
     * @var string
     */
    protected $downloadLink;

    protected function init()
    {
        parent::init();

        $this->icon->image = ico('update32')->image;
    }


    /**
     * @param $data
     * @param bool $always
     * @return bool
     */
    public function tryShow($data, $always = false)
    {
        $lastUpdateVersion = Ide::get()->getUserConfigValue('lastUpdateVersion');

        if ($lastUpdateVersion && $data['hash'] === $lastUpdateVersion && !$always) {
            return false;
        }

        $this->descriptionField->text = $data['description'];
        $this->nameLabel->text = $data['name'];
        $this->downloadLink = $data['url'];
        $this->videoLink = $data['video'];

        Ide::get()->setUserConfigValue('lastUpdateVersion', $data['hash']);

        UXApplication::runLater(function () {
            Notifications::show('Доступно обновление', 'Вышла новая версия нашей среды разработки, попробуйте её уже сегодня!', 'INFORMATION');

            $this->showAndWait();
        });

        return true;
    }

    /**
     * @event show
     */
    public function doShow()
    {
        $this->downloadButton->enabled = !!$this->downloadLink;
        $this->youtubeButton->enabled = !!$this->videoLink;
    }

    /**
     * @event youtubeButton.action
     */
    public function doYoutube()
    {
        $desktop = new UXDesktop();
        $desktop->browse($this->videoLink);
    }

    /**
     * @event downloadButton.action
     */
    public function doDownload()
    {
        $desktop = new UXDesktop();
        $desktop->browse($this->downloadLink);
        $this->hide();
    }

    /**
     * @event close
     * @event cancelButton.action
     * @param UXEvent $e
     */
    public function doCancel(UXEvent $e)
    {
        $dialog = new MessageBoxForm('Вы уверены, что не хотите обновится до новой версии?', ['Да, обновиться позже', 'Отмена']);

        if ($dialog->showDialog() && $dialog->getResultIndex() == 1) {
            $e->consume();
        } else {
            $this->hide();
        }
    }
}