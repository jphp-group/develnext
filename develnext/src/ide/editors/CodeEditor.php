<?php
namespace ide\editors;

use Files;
use ide\autocomplete\php\PhpAutoComplete;
use ide\autocomplete\ui\AutoCompletePane;
use ide\editors\menu\ContextMenu;
use ide\Logger;
use ide\misc\AbstractCommand;
use ide\misc\EventHandlerBehaviour;
use ide\utils\FileUtils;
use ide\utils\Json;
use ide\utils\UiUtils;
use php\format\JsonProcessor;
use php\gui\designer\UXSyntaxAutoCompletion;
use php\gui\designer\UXSyntaxTextArea;
use php\gui\event\UXKeyEvent;
use php\gui\event\UXWebErrorEvent;
use php\gui\event\UXWebEvent;
use php\gui\framework\Timer;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\UXApplication;
use php\gui\UXCheckbox;
use php\gui\UXClipboard;
use php\gui\UXContextMenu;
use php\gui\UXDesktop;
use php\gui\UXDialog;
use php\gui\UXForm;
use php\gui\UXListView;
use php\gui\UXMenuItem;
use php\gui\UXNode;
use php\gui\UXPopupWindow;
use php\gui\UXWebEngine;
use php\gui\UXWebView;
use php\io\File;
use php\io\IOException;
use php\io\ResourceStream;
use php\io\Stream;
use php\lang\IllegalArgumentException;
use php\lang\IllegalStateException;
use php\lib\Char;
use php\lib\Items;
use php\lib\Mirror;
use php\lib\Str;
use php\net\URLConnection;
use php\time\Time;
use php\util\Scanner;

/**
 * Class CodeEditor
 * @package ide\editors
 */
class CodeEditor extends AbstractEditor
{
    use EventHandlerBehaviour;

    protected $mode;

    /**
     * @var bool
     */
    protected $lockHandlers = false;

    /**
     * @var null|array
     */
    protected $editableArea = null;

    /**
     * @var array
     */
    protected $doOnSucceed = [];

    /**
     * @var AbstractCommand[]
     */
    protected $commands = [];

    /**
     * @var UXSyntaxTextArea
     */
    protected $textArea;

    /**
     * @var AutoCompletePane
     */
    protected $autoComplete;

    public function getIcon()
    {
        return $this->mode ? 'icons/' . $this->mode . 'File16.png' : null;
    }

    public function __construct($file, $mode = 'php', $options = array())
    {
        parent::__construct($file);

        $this->mode = $mode;

        $this->textArea = new UXSyntaxTextArea();
        $this->textArea->syntaxStyle = "text/$mode";

        if ($mode == 'php') {
            $this->autoComplete = new AutoCompletePane($this->textArea, new PhpAutoComplete());
        }

        $this->textArea->on('keyUp', function (UXKeyEvent $e) {
            $this->doChange();
        });
    }

    public function setReadOnly($value)
    {
        $this->textArea->editable = !$value;
    }

    public function installAutoCompletion(UXSyntaxAutoCompletion $completion)
    {
        $completion->install($this->textArea);
    }

    /**
     * @param $any
     *
     * @throws IllegalArgumentException
     */
    public function register($any)
    {
        if ($any instanceof AbstractCommand) {
            $any->setTarget($this);
            $this->commands[] = $any;
        } else {
            throw new IllegalArgumentException();
        }
    }

    public $__eventUpdates = 0;

    protected function doChange()
    {
        $i = ++$this->__eventUpdates;

        Timer::run(1000, function () use ($i) {
            if ($i == $this->__eventUpdates) {
                $this->trigger('update', []);
            }
        });
    }

    public function executeCommand($command)
    {
        switch ($command) {
            case 'undo': $this->textArea->undo(); break;
            case 'redo': $this->textArea->redo(); break;
            case 'copy': $this->textArea->copy(); break;
            case 'cut': $this->textArea->cut(); break;
            case 'paste': $this->textArea->paste(); break;
            case 'find': $this->textArea->showFindDialog(); break;
            case 'replace': $this->textArea->showReplaceDialog(); break;

            default:
                ;
        }
    }

    public function getTitle()
    {
        return File::of($this->file)->getName();
    }

    public function jumpToLine($line, $offset = 0)
    {
        $this->textArea->requestFocus();
        $this->textArea->jumpToLine($line, $offset);
    }

    public function getValue()
    {
        return $this->textArea->text;
    }

    public function setValue($value)
    {
        $this->textArea->text = $value;
    }

    public function load()
    {
        if (!$this->file) {
            return;
        }

        $sourceFile = "$this->file.source";

        if (Files::exists($sourceFile)) {
            $file = $sourceFile;
        } else {
            $file = $this->file;
        }

        Logger::info("Start load file $file");

        try {
            $content = FileUtils::get($file);
        } catch (IOException $e) {
            $content = '';
            Logger::warn("Unable to load $file: {$e->getMessage()}");
        }

        $this->setValue($content);

        Logger::info("Finish load file $file");
    }

    public function save()
    {
        if (!$this->file) {
            return;
        }

        Logger::info("Start save file $this->file ...");

        $value = $this->getValue();

        if (!Files::exists($this->file)) {
            FileUtils::put($this->file, $value);
        }

        FileUtils::put("$this->file.source", $value);

        Logger::info("Finish save file $this->file.");
    }

    /**
     * @return UXNode
     */
    public function makeUi()
    {
        $ui = new UXAnchorPane();

        $commandPane = UiUtils::makeCommandPane($this->commands);
        $commandPane->padding = 3;
        $commandPane->spacing = 4;
        $commandPane->fillHeight = true;
        $commandPane->height = 30;

        $ui->add($commandPane);
        $ui->add($this->textArea);

        UXAnchorPane::setAnchor($commandPane, 0);
        UXAnchorPane::setAnchor($this->textArea, 0);

        $commandPane->bottomAnchor = null;
        $this->textArea->topAnchor = 30;

        return $ui;
    }

    public function registerDefaultCommands()
    {
        $this->register(AbstractCommand::make('Отменить (Ctrl + Z)', 'icons/undo16.png', function () {
            $this->executeCommand('undo');
        }));

        $this->register(AbstractCommand::make('Вернуть (Ctrl + Shift + Z)', 'icons/redo16.png', function () {
            $this->executeCommand('redo');
        }));

        $this->register(AbstractCommand::makeSeparator());

        $this->register(AbstractCommand::make('Вырезать (Ctrl + X)', 'icons/cut16.png', function () {
            $this->executeCommand('cut');
        }));

        $this->register(AbstractCommand::make('Копировать (Ctrl + C)', 'icons/copy16.png', function () {
            $this->executeCommand('copy');
        }));

        $this->register(AbstractCommand::make('Вставить (Ctrl + V)', 'icons/paste16.png', function () {
            $this->executeCommand('paste');
        }));

        $this->register(AbstractCommand::makeSeparator());

        $this->register(AbstractCommand::makeWithText('Найти', 'icons/search16.png', function () {
            $this->executeCommand('find');
        }));

        $this->register(AbstractCommand::makeWithText('Заменить', 'icons/replace16.png', function () {
            $this->executeCommand('replace');
            $this->save();
        }));
    }

    public function requestFocus()
    {
        $this->textArea->requestFocus();
    }
}

class SetDefaultCommand extends AbstractCommand
{
    /**
     * @var FormEditor
     */
    protected $formEditor;

    protected $editor;

    /**
     * SetDefaultCommand constructor.
     * @param FormEditor $formEditor
     * @param $editor
     */
    public function __construct(FormEditor $formEditor, $editor)
    {
        $this->formEditor = $formEditor;
        $this->editor = $editor;
    }

    public function getName()
    {
        return 'Использовать по-умолчанию';
    }

    public function makeUiForHead()
    {
        $ui = new UXCheckbox($this->getName());
        $ui->padding = 3;

        $ui->selected = $this->formEditor->getDefaultEventEditor(false) == "php";

        UXApplication::runLater(function () use ($ui) {
            $ui->watch('selected', function (UXNode $self, $property, $oldValue, $newValue) {
                if ($newValue) {
                    $this->formEditor->setDefaultEventEditor($this->editor);
                } else {
                    $this->formEditor->setDefaultEventEditor($this->editor == 'php' ? 'constructor' : 'php');
                }
            });
        });

        return $ui;
    }

    public function withBeforeSeparator()
    {
        return true;
    }

    public function onExecute()
    {
        ;
    }
}
