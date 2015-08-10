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
        <script src="$codeMirrorPath/codemirror.js"></script>
        <script src="$codeMirrorPath/addon/comment/comment.js"></script>
        <script src="$codeMirrorPath/addon/comment/continuecomment.js"></script>
        <script src="$codeMirrorPath/addon/selection/active-line.js"></script>
        <script src="$codeMirrorPath/addon/selection/selection-pointer.js"></script>
        <script src="$codeMirrorPath/addon/display/placeholder.js"></script>
        #HEAD#

        <style>
          html { height: 100%; }
          body { height: 100%; padding: 0; margin: 0; }
        </style>
    </head>
<body>
    <textarea id="editor"></textarea>
    <script type="text/javascript">
        CODE_EDITOR = CodeMirror.fromTextArea(document.getElementById("editor"), #OPTIONS#);
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

        function trigger(event, args) {
            if (typeof PHP !== "undefined") {
                args = args || [];

                var result = PHP.run(JSON.stringify({event: event, args: args}));

                return result ? JSON.parse(result) : result;
            }
        }

        function setValue(value) {
            unbindEvents();
            CODE_EDITOR.setValue(value);
            bindEvents();
        }

        function getValue() {
            return CODE_EDITOR.getValue();
        }

        function highlightArea(beginLine, endLine) {
            beginLine = parseInt(beginLine);
            endLine = parseInt(endLine);

            for (var i = 0; i < CODE_EDITOR.lineCount(); i++) {
                if (i >= beginLine && i < endLine) {
                    CODE_EDITOR.removeLineClass(i, "background", "highlight-area");
                    CODE_EDITOR.removeLineClass(i, "text", "highlight-area-text");
                } else {
                    CODE_EDITOR.addLineClass(i, "background", "highlight-area");
                    CODE_EDITOR.addLineClass(i, "text", "highlight-area-text");
                }
            }
        }

        function jumpToLine(line, offset) {
            CODE_EDITOR.focus();
            CODE_EDITOR.execCommand('goDocStart');

            //CODE_EDITOR.extendSelection(CodeMirror.Pos(line, offset))
            for (var i = 0; i < line; i++) {
                CODE_EDITOR.moveVEx(1, "line");
            }

            for (var i = 0; i < offset; i++) {
                CODE_EDITOR.execCommand('goCharRight');
            }

            var myHeight = CODE_EDITOR.getScrollInfo().clientHeight;
            var coords = CODE_EDITOR.charCoords({line: line, ch: 0}, "local");
            CODE_EDITOR.scrollTo(null, (coords.top + coords.bottom - myHeight) / 2);
        }

        $(window).load(function(){
            bindEvents();
            alert("~editor:loaded~");
        });
    </script>
</body>
</html>
CONTENT;
        $head = '';

        if ($mode) {
            if (!$options['mode']) {
                $options['mode'] = 'text/x-' . $mode;
            }

            $head .= '<script src="' . $codeMirrorPath . '/mode/htmlmixed/htmlmixed.js"></script>';
            $head .= '<script src="' . $codeMirrorPath . '/mode/css/css.js"></script>';
            $head .= '<script src="' . $codeMirrorPath . '/mode/xml/xml.js"></script>';
            $head .= '<script src="' . $codeMirrorPath . '/mode/javascript/javascript.js"></script>';
            $head .= '<script src="' . $codeMirrorPath . '/mode/clike/clike.js"></script>';

            $head .= '<script src="' . $codeMirrorPath . '/mode/' . $mode . '/' . $mode . '.js"></script>';
        }

        if ($options['theme']) {
            $head .= "<link rel='stylesheet' href='{$codeMirrorPath}/theme/{$options['theme']}.css'>";
        }

        $this->json = new JsonProcessor();

        $content = Str::replace($content, '#HEAD#', $head);
        $content = Str::replace($content, '#OPTIONS#', $this->json->format($options));

        $this->webView = new UXWebView();
        $this->webEngine = $this->webView->engine;

        $this->webEngine->loadContent($content);

        $this->loaded = false;

        $this->webEngine->on('alert', function (UXWebEvent $e) {
            if ($e->data == "~editor:loaded~") {
                $this->loaded = true;
                $this->webEngine->addSimpleBridge('PHP', [$this, 'editorBridgeHandler']);
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