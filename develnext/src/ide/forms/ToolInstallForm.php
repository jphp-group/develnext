<?php
namespace ide\forms;


use ide\tool\AbstractTool;
use ide\tool\AbstractToolInstaller;
use php\gui\UXDialog;
use php\gui\UXLabel;
use php\gui\UXTextArea;

/**
 * Class ToolInstallForm
 * @package ide\forms
 *
 * @property UXLabel $titleLabel
 * @property UXLabel $descriptionLabel
 * @property UXTextArea $console
 */
class ToolInstallForm extends AbstractIdeForm
{
    /**
     * @var AbstractToolInstaller
     */
    private $installer;

    /**
     * ToolInstallForm constructor.
     * @param AbstractToolInstaller $installer
     */
    public function __construct(AbstractToolInstaller $installer)
    {
        $this->installer = $installer;

        parent::__construct();

        $installer->on('progress', function ($status, $progress) {
            uiLater(function () use ($status, $progress) {
                if (!$this->visible) {
                    $this->show();
                    $this->toFront();
                }

                $progress = round($progress, 2);
                $this->descriptionLabel->text = "$status ({$progress}%)";
            });
        }, __CLASS__);

        $installer->on('message', function ($message, $type) {
            uiLater(function () use ($message, $type) {
                $this->console->text .= "[$type] $message\n";
                $this->console->end();
            });
        }, __CLASS__);

        $installer->on('done', function ($success) {
            uiLater(function () use ($success) {
                if ($success) {
                    $this->console->text .= 'All done success.';
                    $this->console->end();

                    waitAsync(2000, [$this, 'hide']);
                    //$this->hide();
                } else {
                    UXDialog::show('Ошибка установки ' . $this->installer->getTool()->getName(), 'ERROR');
                }
            });
        }, __CLASS__);
    }

    protected function init()
    {
        $installer = $this->installer;

        $this->title = "Установка " . $installer->getTool()->getName();
        $this->titleLabel->text = "Установка " . $installer->getTool()->getName();
        $this->icon->image = ico('setup32')->image;
    }
}