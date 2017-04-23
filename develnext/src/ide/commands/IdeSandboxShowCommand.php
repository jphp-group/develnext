<?php
namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\editors\CodeEditor;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\misc\SimpleSingleCommand;
use ide\systems\IdeSystem;
use ide\utils\FileUtils;
use ide\utils\UiUtils;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXVBox;
use php\gui\text\UXFont;
use php\gui\UXForm;
use php\gui\UXTextArea;
use php\lang\Environment;

/**
 * @package ide\commands
 */
class IdeSandboxShowCommand extends AbstractCommand
{
    /**
     * @var AbstractCommand
     */
    public $command;

    public function getName()
    {
        return 'IDE Sandbox';
    }

    public function getCategory()
    {
        return 'help';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        if ($this->command) {
            if (IdeSystem::isDevelopment()) {
                $this->command->onExecute();
            }

            return;
        }

        $ui = new UXVBox();
        $ui->spacing = 5;
        $ui->padding = 5;

        $file = IdeSystem::getFile("sandbox.php");
        $editor = new CodeEditor($file, 'php');
        $editor->setEmbedded(true);
        $editor->setSourceFile(false);
        $editor->registerDefaultCommands();
        $editor->loadContentToArea();

        if (!$editor->getValue()) {
            $editor->setValue("<?\n");
        }

        $editor->on('update', function () use ($editor) {
            $editor->save();
        });

        $pane = UiUtils::makeCommandPane([
            $this->command = SimpleSingleCommand::makeWithText('Запустить', 'icons/run16.png', function () use ($editor, $file) {
                //include $file;

                eval("?>" . $editor->getValue());
            }),
            SimpleSingleCommand::makeWithText('Скрыть', 'icons/square16.png', function () use ($editor) {
                Ide::get()->getMainForm()->hideBottom();
                $editor->lockHandles();
                $this->command = null;
            })
        ]);
        $pane->spacing = 5;
        $pane->minHeight = 25;

        $textArea = $editor->makeUi();
        UXVBox::setVgrow($textArea, 'ALWAYS');

        $ui->add($textArea);
        $ui->add($pane);

        Ide::get()->getMainForm()->showBottom($ui);
    }

    public function isAlways()
    {
        return false;
    }

    public function getAccelerator()
    {
        return 'F11';
    }
}