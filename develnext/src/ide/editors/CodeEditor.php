<?php
namespace ide\editors;

use Files;
use ide\autocomplete\php\PhpAutoComplete;
use ide\autocomplete\ui\AutoCompletePane;
use ide\editors\menu\ContextMenu;
use ide\forms\AbstractIdeForm;
use ide\forms\FindTextDialogForm;
use ide\forms\MessageBoxForm;
use ide\forms\ReplaceTextDialogForm;
use ide\Logger;
use ide\misc\AbstractCommand;
use ide\misc\EventHandlerBehaviour;
use ide\utils\FileUtils;
use ide\utils\Json;
use ide\utils\UiUtils;
use php\format\JsonProcessor;
use php\gui\designer\UXAbstractCodeArea;
use php\gui\designer\UXCssCodeArea;
use php\gui\designer\UXFxCssCodeArea;
use php\gui\designer\UXPhpCodeArea;
use php\gui\designer\UXSyntaxAutoCompletion;
use php\gui\designer\UXSyntaxTextArea;
use php\gui\event\UXKeyEvent;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\UXApplication;
use php\gui\UXCheckbox;
use php\gui\UXClipboard;
use php\gui\UXContextMenu;
use php\gui\UXDesktop;
use php\gui\UXDialog;
use php\gui\UXForm;
use php\gui\UXLabel;
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
use php\lib\fs;
use php\lib\Items;
use php\lib\Mirror;
use php\lib\Str;
use php\net\URLConnection;
use php\time\Time;
use php\util\Scanner;
use script\TimerScript;

/**
 * Class CodeEditor
 * @package ide\editors
 */
class CodeEditor extends AbstractEditor
{
    const USE_NEW_EDITOR = true;

    use EventHandlerBehaviour;

    protected $mode;

    /**
     * @var UXNode
     */
    protected $ui;

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
     * @var UXSyntaxTextArea|UXAbstractCodeArea
     */
    protected $textArea;

    /**
     * @var AutoCompletePane
     */
    protected $autoComplete;


    /**
     * @var FindTextDialogForm
     */
    protected $findDialog;

    /**
     * @var ReplaceTextDialogForm
     */
    protected $replaceDialog;

    /**
     * @var int
     */
    protected $findDialogLastIndex = 0;

    public function getIcon()
    {
        return $this->mode ? 'icons/' . $this->mode . 'File16.png' : null;
    }

    public function __construct($file, $mode = 'php', $options = array())
    {
        parent::__construct($file);

        $this->mode = $mode;

        if (self::USE_NEW_EDITOR) {
            switch ($mode) {
                case 'php':
                    $this->textArea = new UXPhpCodeArea();
                    break;

                case 'css':
                    $this->textArea = new UXCssCodeArea();
                    break;

                case 'fxcss':
                    $this->textArea = new UXFxCssCodeArea();
                    break;
            }
        } else {
            $this->textArea = new UXSyntaxTextArea();
            $this->textArea->syntaxStyle = "text/$mode";
        }

        if ($mode == 'php') {
            $this->autoComplete = new AutoCompletePane($this->textArea, new PhpAutoComplete());
        }

        $this->textArea->on('keyUp', function (UXKeyEvent $e) {
            $this->doChange();
        });

        $this->findDialog = new FindTextDialogForm(function ($text, array $options) {
            $this->findSearchText($text, $options);
        });

        $this->replaceDialog = new ReplaceTextDialogForm(function ($text, $newText, array $options, $command) {
            $this->replaceSearchText($text, $newText, $options, $command);
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

        waitAsync(1000, function () use ($i) {
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

            case 'find':
                if ($this->textArea instanceof UXAbstractCodeArea) {
                    $this->showFindDialog();
                } else {
                    $this->textArea->showFindDialog();
                }
                break;

            case 'replace':
                if ($this->textArea instanceof UXAbstractCodeArea) {
                    $this->showReplaceDialog();
                } else {
                    $this->textArea->showReplaceDialog();
                }
                break;

            default:
                ;
        }
    }

    public function getTitle()
    {
        return $this->format->getTitle($this->file);
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
        $value = str::replace($value, "\t", str::repeat(" ", 4));
        $this->textArea->text = $value;
    }

    public function load()
    {
        if (!$this->file) {
            return;
        }

        if ($this->mode == 'php') {
            $sourceFile = "$this->file.source";

            if (fs::exists($sourceFile)) {
                $file = $sourceFile;
            } else {
                $file = $this->file;
            }
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

        if (!fs::exists($this->file)) {
            FileUtils::put($this->file, $value);
        }

        if ($this->mode == 'php') {
            FileUtils::put("$this->file.source", $value);
        } else {
            FileUtils::put($this->file, $value);
        }

        Logger::info("Finish save file $this->file.");
    }

    /**
     * @return UXNode
     */
    public function makeUi()
    {
        $this->ui = $ui = new UXAnchorPane();

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

        $resize = function () {
            $this->refreshUi();
        };

        $ui->observer('height')->addListener($resize);
        $ui->observer('width')->addListener($resize);

        return $ui;
    }

    public function refreshUi()
    {
        $ui = $this->ui;

        $ui->requestLayout();

        TimerScript::executeAfter(500, function () use ($ui) {
            $ui->requestLayout();
        });

        TimerScript::executeAfter(1000, function () use ($ui) {
            $ui->requestLayout();
        });
    }

    public function makeLeftPaneUi()
    {
        $tmp = new UXLabel('В разработке ...');
        $tmp->style = '-fx-font-style: italic;';
        $tmp->padding = 10;

        return $tmp;
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

    protected function _findSearchText(AbstractIdeForm $dialog, $text, array $options, $silent = false)
    {
        $len = $pos = 0;

        Logger::debug("Find search text '$text', from {$this->findDialogLastIndex}");

        $case = $options['case'];
        $method = str::class . '::' . ($case ? 'pos' : 'posIgnoreCase');

        if ($options['wholeText']) {
            $words = [$text];
        } else {
            $words = str::split($text, ' ');
        }

        foreach ($words as $word) {
            $pos = $method($this->textArea->text, $word, $this->findDialogLastIndex);
            $len = str::length($word);

            if ($pos > -1) {
                break;
            }
        }

        if ($pos == -1) {
            if ($this->findDialogLastIndex == 0) {
                if (!$silent) {
                    UXDialog::showAndWait('Ничего не найдено.');
                    $dialog->show();
                }

                return null;
            }

            if (!$silent && MessageBoxForm::confirm('Больше ничего не найдено, начать сначала?')) {
                $this->findDialogLastIndex = 0;
                $dialog->show();
                $this->_findSearchText($dialog, $text, $options);
            }

            return null;
        }

        $this->findDialogLastIndex = $pos + 1;
        $this->textArea->caretPosition = $pos;
        $this->textArea->select($pos, $pos + $len);

        Logger::debug("Find select [$pos, $len]");

        return [$pos, $len];
    }

    protected function findSearchText($text, array $options)
    {
        return $this->_findSearchText($this->findDialog, $text, $options);
    }

    protected function replaceSearchText($text, $newText, $options, $command)
    {
        Logger::debug("Replace search text '$text', from {$this->findDialogLastIndex}");

        $result = null;

        switch ($command) {
            case 'START':
                $this->findDialogLastIndex = 0;
            // continue.

            case 'SKIP':
                $result = $this->_findSearchText($this->replaceDialog, $text, $options, true);
                break;

            case 'REPLACE':
                if (!$this->textArea->selectedText) {
                    UXDialog::showAndWait('Ничего не найдено.');
                    break;
                }

                $this->textArea->selectedText = $newText;
                $end = $this->findDialogLastIndex - 1 + str::length($newText);

                $this->textArea->select($this->findDialogLastIndex - 1, $end);
                $this->findDialogLastIndex = $end + 1;

                $result = $this->_findSearchText($this->replaceDialog, $text, $options, true);

                if (!$result) {
                    $this->textArea->select(0, 0);
                }

                break;
        }

        $this->replaceDialog->setFindResult($result);
    }

    public function showFindDialog()
    {
        $this->findDialogLastIndex = 0;

        if ($this->textArea->selectedText) {
            $this->findDialog->setResult($this->textArea->selectedText);
        }

        $this->findDialog->show();
    }

    public function showReplaceDialog()
    {
        $this->findDialogLastIndex = 0;

        if ($this->textArea->selectedText) {
            $this->replaceDialog->setResult($this->textArea->selectedText);
        }

        $this->replaceDialog->show();
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

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        //
    }
}
