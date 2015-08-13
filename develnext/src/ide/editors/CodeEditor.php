<?php
namespace ide\editors;

use ide\misc\AbstractCommand;
use ide\utils\FileUtils;
use ide\utils\Json;
use ide\utils\UiUtils;
use php\format\JsonProcessor;
use php\gui\event\UXWebErrorEvent;
use php\gui\event\UXWebEvent;
use php\gui\framework\Timer;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\UXApplication;
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

        $url = new ResourceStream('/.data/vendor/codemirror/codemirror.css');

        $codeMirrorPath = $url->toExternalForm();
        $codeMirrorPath = Str::replace($codeMirrorPath, '/codemirror.css', '');

        $url = new ResourceStream('/.data/vendor/jquery/jquery-1.11.1.js');
        $jqueryPath = $url->toExternalForm();

        $content = <<<"CONTENT"
<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href="$codeMirrorPath/codemirror.css">
        <script src="$jqueryPath"></script>
        <script src="$acePath/ace.js"></script>

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
<body>
    <pre id="editor"></pre>
    <script type="text/javascript">
        CODE_EDITOR = ace.edit("editor");
        CODE_EDITOR.setTheme("ace/theme/#THEME#");
        CODE_EDITOR.getSession().setMode("ace/mode/#MODE#");

        document.getElementById('editor').style.fontSize='14px';

        /*CODE_EDITOR = CodeMirror.fromTextArea(document.getElementById("editor"), #OPTIONS#);
        CODE_EDITOR.setSize("100%", "100%");

        function bindEvents() {
            CODE_EDITOR.on('beforeChange', function (cm, change) {
                var result = trigger('beforeChange', change);

                if (result && result["cancel"]) {
                    change.cancel();
                }
            });

            CODE_EDITOR.on('change', function (cm, change) {
                 change.value = getValue();
                 trigger('change', change)
            });
            CODE_EDITOR.on('cursorActivity', function (cm) { trigger('cursorActivity') });
            CODE_EDITOR.on('keyHandled', function (cm, name, e) { trigger('keyHandled', {name: name, event: event}) });
            CODE_EDITOR.on('gutterClick', function (cm, line, gutter) { trigger('keyHandled', {line: line, gutter: gutter}) });
        }

        function unbindEvents() {
            CODE_EDITOR.off('beforeChange');
            CODE_EDITOR.off('change');
            CODE_EDITOR.off('cursorActivity');
            CODE_EDITOR.off('keyHandled');
            CODE_EDITOR.off('gutterClick');
        }
        */

        function trigger(event, args) {
            if (typeof PHP !== "undefined") {
                args = args || [];

                var result = PHP.run(JSON.stringify({event: event, args: args}));

                return result ? JSON.parse(result) : result;
            }
        }

        function setValue(value) {
            //unbindEvents();
            CODE_EDITOR.setValue(value, -1);
            //bindEvents();
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
            alert("~editor:loaded~");
        });
    </script>
</body>
</html>
CONTENT;

        $this->json = new JsonProcessor();

        $content = Str::replace($content, '#MODE#', $mode);
        $content = Str::replace($content, '#THEME#', $options['theme']);

        $this->webView = new UXWebView();
        $this->webEngine = $this->webView->engine;

        $this->webEngine->loadContent($content);

        $this->loaded = false;

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

        $this->on('beforeChange', [$this, 'doBeforeChange']);
        $this->on('change', [$this, 'doChange']);
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
            $this->commands[Mirror::typeOf($any, true)] = $any;
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

        $this->value = $change['value'];

        Timer::run(1000, function () use ($i) {
            if ($i == $this->eventUpdates) {
                $this->trigger('update', []);
            }
        });
    }

    protected function doBeforeChange($change)
    {
        if ($this->editableArea) {
            $line = $change['from']['line'];
            // TODO: сделать плавающий endLine

            if ($line >= $this->editableArea['endLine'] || $line < $this->editableArea['beginLine']) {
                return ['cancel' => true];
            }
        }
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
        try {
            $content = FileUtils::get($this->file);
        } catch (IOException $e) {
            $content = '';
        }

        $this->setValue($content);
    }

    public function save()
    {
        $value = $this->getValue();
        FileUtils::put($this->file, $value);
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
}
