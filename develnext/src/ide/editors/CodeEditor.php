<?php
namespace ide\editors;

use php\format\JsonProcessor;
use php\gui\event\UXWebErrorEvent;
use php\gui\event\UXWebEvent;
use php\gui\framework\Timer;
use php\gui\layout\UXAnchorPane;
use php\gui\UXDialog;
use php\gui\UXNode;
use php\gui\UXWebEngine;
use php\gui\UXWebView;
use php\io\File;
use php\io\IOException;
use php\io\ResourceStream;
use php\io\Stream;
use php\lib\Str;
use php\net\URLConnection;

/**
 * Class CodeEditor
 * @package ide\editors
 */
class CodeEditor extends AbstractEditor
{
    protected $webView;

    protected $mode;

    /**
     * @var UXWebEngine
     */
    protected $webEngine;

    protected $json;

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

        $content = <<<"CONTENT"
<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href="$codeMirrorPath/codemirror.css">
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

        CODE_EDITOR.on('change', function (cm, e) {
            if (typeof PHP !== "undefined") {
                PHP.run('change', e);
            }
        });

        function setValue(value) {
            CODE_EDITOR.setValue(value);
        }

        function getValue() {
            return CODE_EDITOR.getValue();
        }
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

        $this->webEngine->on('alert', function (UXWebEvent $e) {
            UXDialog::show($e->data);
        });

        $this->webEngine->on('error', function (UXWebErrorEvent $e) {
            UXDialog::show($e->message);
        });

        $this->webEngine->waitState('SUCCEEDED', function () {
            // fixme it doesn't work properly
            $this->webEngine->addBridge('PHP', [$this, 'editorBridgeHandler']);
        });
    }

    protected function editorBridgeHandler($event, ...$args)
    {
        // todo!!!!!!!!!!!!
        UXDialog::show($event);
    }

    public function getTitle()
    {
        return File::of($this->file)->getName();
    }

    public function load()
    {
        try {
            $content = Stream::getContents($this->file);
        } catch (IOException $e) {
            $content = '';
        }

        $this->webEngine->waitState('SUCCEEDED', function () use ($content) {
            $this->webEngine->callFunction('setValue', [$content]);
        });
    }

    public function save()
    {
        $this->webEngine->waitState('SUCCEEDED', function () {
            $content = $this->webEngine->callFunction('getValue', []);
            Stream::putContents($this->file, $content);
        });
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
        $ui->add($this->webView);

        UXAnchorPane::setBottomAnchor($this->webView, 0);
        UXAnchorPane::setTopAnchor($this->webView, 0);
        UXAnchorPane::setLeftAnchor($this->webView, 0);
        UXAnchorPane::setRightAnchor($this->webView, 0);

        return $ui;
    }
}