<?php
namespace ide\editors\common;

use ide\utils\Json;
use php\format\JsonProcessor;
use php\gui\event\UXKeyEvent;
use php\gui\event\UXWebEvent;
use php\gui\framework\Timer;
use php\gui\UXApplication;
use php\gui\UXClipboard;
use php\gui\UXDialog;
use php\gui\UXWebView;
use php\io\ResourceStream;
use php\lib\Str;

class CodeTextArea extends UXWebView
{
    protected static $eventTypes = ['change' => 1, 'copy' => 1, 'cut' => 1, 'paste' => 1, 'context' => 1];
    
    /**
     * @var bool
     */
    protected $loaded = false;
    
    /**
     * @var null|string
     */
    protected $value = null;
    
    /**
     * @var array
     **/
    protected $handlers = [];
    
    /**
     * @var array
     */
    protected $doOnSucceed = [];
    
    /**
     * @var bool
     */
    protected $lockHandlers = false;
    
    /**
     * CodeTextArea constructor.
     * @param $mode
     * @param array $options
     */
    public function __construct($mode, array $options = [])
    {
        parent::__construct();
        
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
        
        $this->engine->loadContent($content);
        
        $this->loaded = false;
        
        $this->on('keyDown', function (UXKeyEvent $e) {
            if (($e->controlDown && $e->codeName == 'V')
                || ($e->shiftDown && $e->codeName == 'Insert')
            ) {
                UXApplication::runLater(function () {
                    $this->executeCommand('paste');
                });
            }
        });
        
        $this->engine->on('alert', function (UXWebEvent $e) {
            if ($e->data == "~editor:loaded~") {
                UXApplication::runLater(function () {
                    $this->loaded = true;
                    $this->engine->addSimpleBridge('PHP', [$this, 'editorBridgeHandler']);
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
        
        $this->engine->watchState(function ($self, $old, $new) use ($applySucceed) {
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
        //$this->on('context', [$this, 'doContextMenu']);
    }
    
    protected function waitState(callable $handler)
    {
        if ($this->engine->state == 'SUCCEEDED' || $this->loaded) {
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
        $this->requestFocus();
        
        UXApplication::runLater(function () use ($command) {
            $this->waitState(function () use ($command) {
                $this->engine->callFunction('executeCommand', [$command]);
                $this->value = $this->engine->callFunction('getValue', []);
            });
        });
    }
    
    public function triggerCustom($event, array $args)
    {
        if (static::$eventTypes[$event]) {
            $result = null;
            
            foreach ((array)$this->handlers[$event] as $handler) {
                $result = $handler($args);
                
                if ($result) {
                    return $result;
                }
            }
        } else {
            parent::trigger($event);
            return null;
        }
    }
    
    public function on($event, callable $handler, $group = 'general')
    {
        if (static::$eventTypes[$event]) {
            $this->handlers[$event][$group] = $handler;
        } else {
            parent::on($event, $handler, $group);
        }
    }
    
    public function off($event, $group = null)
    {
        if (static::$eventTypes[$event]) {
            if ($group === null) {
                unset($this->handlers[$event]);
            } else {
                unset($this->handlers[$event][$group]);
            }
        } else {
            parent::off($event, $group);
        }
    }
    
    protected function editorBridgeHandler($data)
    {
        if ($this->lockHandlers) {
            return null;
        }
        
        $data = Json::decode($data);
        
        $event = $data['event'];
        $args = $data['args'];
        
        $result = $this->triggerCustom($event, $args);
        
        return Json::encode($result);
    }
    
    private $eventUpdates = 0;
    
    protected function doChange($change)
    {
        $i = ++$this->eventUpdates;
        
        Timer::run(1000, function () use ($i) {
            $this->value = $this->engine->callFunction('getValue', []);
            
            if ($i == $this->eventUpdates) {
                $this->triggerCustom('update', []);
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

    public function jumpToLine($line, $offset = 0)
    {
        UXApplication::runLater(function () use ($line, $offset) {
            $this->waitState(function () use ($line, $offset) {
                $this->requestFocus();
                $this->engine->callFunction('jumpToLine', [$line, $offset]);
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
        
        UXApplication::runLater(function () use ($value) {
            $this->waitState(function () use ($value) {
                $this->engine->callFunction('setValue', [$value]);
            });
        });
    }
}