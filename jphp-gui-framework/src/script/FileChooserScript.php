<?php
namespace script;

use behaviour\SetTextBehaviour;
use php\gui\framework\AbstractScript;
use php\gui\framework\behaviour\TextableBehaviour;
use php\gui\framework\behaviour\ValuableBehaviour;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\gui\UXFileChooser;
use php\gui\UXNode;
use php\io\File;
use php\lang\InterruptedException;
use php\lang\Thread;
use php\lib\Items;
use php\lib\str;
use php\xml\DomDocument;
use script\support\ScriptHelpers;

/**
 * Class TimerScript
 * @package script
 */
class FileChooserScript extends AbstractScript implements TextableBehaviour, SetTextBehaviour, ValuableBehaviour
{
    use ScriptHelpers;

    /**
     * @var UXFileChooser
     */
    protected $_dialog;

    /**
     * @var File
     */
    public $file;

    /**
     * @var File[]
     */
    public $files;

    /**
     * @var null
     */
    public $filterExtensions = null;

    /**
     * @var null
     */
    public $filterName = null;

    /**
     * @var bool
     */
    public $filterAny = true;

    /**
     * @var string
     */
    public $inputNode;

    /**
     * @var string
     */
    public $actionNode;

    /**
     * @var bool
     */
    public $multiple = false;

    /**
     * @var bool
     */
    public $saveDialog = false;

    function __construct()
    {
        $this->_dialog = new UXFileChooser();
    }

    public function execute()
    {
        $anyFilter = false;

        if ($this->filterExtensions || $this->filterName) {
            $extensions = null;

            if ($this->filterExtensions) {
                $extensions = str::split($this->filterExtensions, ',');
                $extensions = flow($extensions)->map([str::class, 'trim'])->toArray();
            }

            $extensions = $extensions ?: ['*.*'];

            if ($extensions == ['*.*']) {
                $anyFilter = true;
            }

            $this->_dialog->extensionFilters = [
                ['description' => $this->filterName ?: $this->filterExtensions, 'extensions' => $extensions]
            ];
        }

        if (!$anyFilter && $this->filterAny) {
            $this->_dialog->extensionFilters[] = [
                'description' => 'All files (*.*)',
                'extensions'  => ['*.*']
            ];
        }

        if ($this->multiple) {
            $file = $this->saveDialog ? $this->_dialog->showSaveDialog() : $this->_dialog->showOpenMultipleDialog();
        } else {
            $file = $this->saveDialog ? $this->_dialog->showSaveDialog() : $this->_dialog->showOpenDialog();
        }

        if ($file !== null) {
            $this->file = is_array($file) ? Items::first($file) : $file;
            $this->files = is_array($file) ? $file : [$file];

            $this->_adaptValue($this->inputNode, $this->files);

            $this->trigger('action');
        } else {
            $this->file = null;
            $this->files = [];

            $this->trigger('cancel');
        }
    }

    /**
     * @param $target
     * @return mixed
     */
    protected function applyImpl($target)
    {
        $this->_bindAction($this->actionNode, function () {
            $this->execute();
        });
    }

    function getObjectText()
    {
        return (string) $this->file;
    }

    function getObjectValue()
    {
        return $this->_dialog->initialDirectory;
    }

    function setObjectValue($value)
    {
        $this->_dialog->initialDirectory = $value;
    }

    function setTextBehaviour($text)
    {
        $this->_dialog->title = $text;
    }

    function appendTextBehaviour($text)
    {
        $this->_dialog->title .= $text;
    }

    function appendObjectValue($value)
    {
        $this->_dialog->initialDirectory .= $value;
    }
}