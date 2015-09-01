<?php
namespace ide\editors;

use Files;
use ide\editors\menu\ContextMenu;
use ide\misc\AbstractCommand;
use ide\utils\FileUtils;
use ide\utils\Json;
use ide\utils\UiUtils;
use php\format\JsonProcessor;
use php\gui\event\UXKeyEvent;
use php\gui\event\UXWebErrorEvent;
use php\gui\event\UXWebEvent;
use php\gui\framework\Timer;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\UXApplication;
use php\gui\UXClipboard;
use php\gui\UXDialog;
use php\gui\UXNode;
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
    protected $loaded;
    /**
     * @var UXWebView
     */
    protected $webView;

    protected $mode;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var UXWebEngine
     */
    protected $webEngine;

    /**
     * @var JsonProcessor
     */
    protected $json;

    /** @var array */
    protected $handlers = [];

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
     * @var ContextMenu
     */
    protected $contextMenu;

    public function getIcon()
    {
        return $this->mode ? 'icons/' . $this->mode . 'File16.png' : null;
    }

    public function __construct($file, $mode = null, $options = array())
    {
        parent::__construct($file);

        $this->mode = $mode;

        $ace = new ResourceStream('/.data/vendor/ace/ace.js');
        $acePath = $ace->toExternalForm();
        $acePath = Str::replace($acePath, '/ace.js', '');

        $url = new ResourceStream('/.data/vendor/jquery/jquery-1.11.1.js');
        $jqueryPath = $url->toExternalForm();

        $content = <<<"CONTENT"
<!doctype html>
<html>
    <head>
        <script src="$jqueryPath"></script>
        <script src="$acePath/ace.js"></script>
        <script src="$acePath/ext-language_tools.js"></script>
        <script src="$acePath/ext-spellcheck.js"></script>
        <script src="$acePath/ext-searchbox.js"></script>
        <script src="$acePath/ext-split.js"></script>
        <style>
          html { height: 100%; }
          body { height: 100%; padding: 0; margin: 0; }

          #editor {
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
            }
        </style>
    </head>
<body><div id="editor"></div><div id="statusBar"></div>
    <script type="text/javascript">
        CODE_EDITOR = ace.edit("editor");

        var lang = ace.require("ace/ext/language_tools");

        CODE_EDITOR.setOptions(#OPTIONS#);

        CODE_EDITOR.setTheme("ace/theme/#THEME#");
        CODE_EDITOR.getSession().setMode("ace/mode/#MODE#");

        function bindEvents() {
            CODE_EDITOR.on('change', function (e) { trigger('change', e) });
            CODE_EDITOR.on('copy', function (e) { trigger('copy', {text: e}) });
            CODE_EDITOR.on('paste', function (e) {
                var result = trigger('paste', e);
                e.text = result.text;
            });

            CODE_EDITOR.on('cut', function (e) { trigger('cut', {text: e}) });
        }

        function unbindEvents() {
            CODE_EDITOR.off('change');
            CODE_EDITOR.off('copy');
            CODE_EDITOR.off('cut');
            CODE_EDITOR.off('paste');
        }

        function executeCommand(cm) {
            CODE_EDITOR.execCommand(cm);
        }

        function trigger(event, args) {
            if (typeof PHP !== "undefined") {
                args = args || [];

                var result = PHP.run(JSON.stringify({event: event, args: args}));

                return result ? JSON.parse(result) : result;
            }
        }

        function setValue(value) {
            unbindEvents();
            CODE_EDITOR.setValue(value, -1);
            bindEvents();
        }

        function getValue() {
            return CODE_EDITOR.getValue();
        }

        function jumpToLine(line, offset) {
            CODE_EDITOR.focus();
            CODE_EDITOR.gotoLine(parseInt(line) + 1, offset, true);
        }

        $(window).load(function(){
            CODE_EDITOR.clearSelection();
            bindEvents();
            alert("~editor:loaded~");
        });

        var completer = {
          getCompletions: function (editor, session, pos, prefix, callback) {
            if (prefix.length === 0) {
                callback(null, []);
                return;
            }

            var result = CODE_EDITOR.trigger('autocomplete', {prefix: prefix, pos: pos}) || [];
            callback(null, []);
            return;
          }
        }

        //lang.addCompleter(completer);
    </script>
</body>
</html>
CONTENT;

        $this->json = new JsonProcessor();

        $content = Str::replace($content, '#MODE#', $mode);
        $content = Str::replace($content, '#THEME#', $options['theme']);
        $content = Str::replace($content, '#OPTIONS#', Json::encode($options));

        $this->webView = new UXWebView();
        $this->webEngine = $this->webView->engine;

        $this->webEngine->loadContent($content);

        $this->loaded = false;

        $this->webView->on('keyDown', function (UXKeyEvent $e) {
            if (($e->controlDown && $e->codeName == 'V')
                || ($e->shiftDown && $e->codeName == 'Insert')) {
                UXApplication::runLater(function () {
                    $this->executeCommand('paste');
                });
            }
        });

        $this->webEngine->on('alert', function (UXWebEvent $e) {
            if ($e->data == "~editor:loaded~") {
                UXApplication::runLater(function () {
                    $this->loaded = true;
                    $this->webEngine->addSimpleBridge('PHP', [$this, 'editorBridgeHandler']);
                });
            } else {
                UXDialog::show($e->data);
            }
        });

        $applySucceed = function () {
            $doOnSucceed = $this->doOnSucceed;
            $this->doOnSucceed = [];

            UXApplication::runLater(function () use ($doOnSucceed) {
                foreach ($doOnSucceed as $handler) {
                    $handler();
                }
            });
        };

        $this->webEngine->watchState(function ($self, $old, $new) use ($applySucceed) {
            if ($new == 'SUCCEEDED') {
                if (!$this->loaded) {
                    Timer::run(100, $applySucceed);
                    return;
                }

                $applySucceed();
            }
        });

        $this->on('change', [$this, 'doChange']);
        $this->on('copy', [$this, 'doCopy']);
        $this->on('cut', [$this, 'doCopy']);
        $this->on('paste', [$this, 'doPaste']);
        $this->on('context', [$this, 'doContextMenu']);
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

    protected function editorBridgeHandler($data)
    {
        if ($this->lockHandlers) {
            return null;
        }

        $data = Json::decode($data);

        $event = $data['event'];
        $args  = $data['args'];

        $result = $this->trigger($event, $args);

        return Json::encode($result);
    }

    private $eventUpdates = 0;

    protected function doChange($change)
    {
        $i = ++$this->eventUpdates;

        Timer::run(1000, function () use ($i) {
            $this->value = $this->webEngine->callFunction('getValue', []);

            if ($i == $this->eventUpdates) {
                $this->trigger('update', []);
            }
        });
    }

    protected function doCopy($e)
    {
        if (is_array($e['text'])) {
            return;
        }

        UXClipboard::setText($e['text']);
    }

    protected function doPaste($e)
    {
        return ['text' => UXClipboard::getText()];
    }

    protected function doContextMenu($e)
    {
        dump('111');
        $this->contextMenu = new ContextMenu($this, $this->commands);
        $this->contextMenu->getRoot()->showByNode($this->webView);
    }

    protected function waitState(callable $handler)
    {
        if ($this->webEngine->state == 'SUCCEEDED' || $this->loaded) {
            $func = null;
            $func = function () use ($handler, &$func) {
                if (!$this->loaded) {
                    Timer::run(100, $func);
                    return;
                }

                $handler();
            };

            $func();
            return;
        }

        $this->doOnSucceed[] = $handler;
    }

    public function executeCommand($command)
    {
        $this->webView->requestFocus();

        UXApplication::runLater(function () use ($command) {
            $this->waitState(function () use ($command) {
                $this->webEngine->callFunction('executeCommand', [$command]);
                $this->value = $this->webEngine->callFunction('getValue', []);
            });
        });
    }

    public function setEditableArea($beginLine, $endLine)
    {
        $this->editableArea = [
            'beginLine' => $beginLine,
            'endLine' => $endLine,
        ];

        $this->waitState(function () use ($beginLine, $endLine) {
            $this->webEngine->callFunction('highlightArea', [$beginLine, $endLine]);
        });
    }

    public function trigger($event, array $args)
    {
        $result = null;

        foreach ((array) $this->handlers[$event] as $handler) {
            $result = $handler($args);

            if ($result) {
                return $result;
            }
        }
    }

    public function on($event, callable $handler, $group = 'general')
    {
        $this->handlers[$event][$group] = $handler;
    }

    public function off($event, $group = null)
    {
        if ($group === null) {
            unset($this->handlers[$event]);
        } else {
            unset($this->handlers[$event][$group]);
        }
    }

    public function getTitle()
    {
        return File::of($this->file)->getName();
    }

    public function jumpToLine($line, $offset = 0)
    {
        UXApplication::runLater(function() use ($line, $offset) {
            $this->waitState(function () use ($line, $offset) {
                $this->webView->requestFocus();
                $this->webEngine->callFunction('jumpToLine', [$line, $offset]);
            });
        });
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;

        UXApplication::runLater(function() use ($value) {
            $this->waitState(function () use ($value) {
                $this->webEngine->callFunction('setValue', [$value]);
            });
        });
    }

    public function load()
    {
        $sourceFile = "$this->file.source";

        if (Files::exists($sourceFile)) {
            $file = $sourceFile;
        } else {
            $file = $this->file;
        }

        try {
            $content = FileUtils::get($file);
        } catch (IOException $e) {
            $content = '';
        }

        $this->setValue($content);
    }

    public function save()
    {
        $value = $this->getValue();

        if (!Files::exists($this->file)) {
            FileUtils::put($this->file, $value);
        }

        FileUtils::put("$this->file.source", $value);
    }

    /**
     * @return UXWebView
     */
    public function getWebView()
    {
        return $this->webView;
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
        $ui->add($this->webView);

        UXAnchorPane::setAnchor($commandPane, 0);
        UXAnchorPane::setAnchor($this->webView, 0);

        $commandPane->bottomAnchor = null;
        $this->webView->topAnchor = 30;

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
}
