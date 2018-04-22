<?php
namespace script;

use behaviour\SetTextBehaviour;
use php\framework\Logger;
use php\gui\framework\AbstractScript;
use php\gui\framework\behaviour\TextableBehaviour;
use php\gui\framework\behaviour\ValuableBehaviour;
use php\gui\UXFileChooser;
use php\io\File;
use php\lib\arr;
use php\lib\fs;
use php\lib\str;
use script\support\ScriptHelpers;

/**
 * Class TimerScript
 * @package script
 *
 * @packages framework
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
     * @var string
     */
    public $initialDirectory;

    /**
     * @var string
     */
    public $initialFileName;

    /**
     * @var bool
     */
    public $saveDialog = false;

    function __construct()
    {
        $this->_dialog = new UXFileChooser();
    }

    /**
     * @return File
     */
    public function execute()
    {
        $extensionFilters = [];

        if ($this->filterExtensions || $this->filterName) {
            $extensions = null;

            if ($this->filterExtensions) {
                $extensions = str::split($this->filterExtensions, ',');
                $extensions = flow($extensions)->map([str::class, 'trim'])->toArray();
            }

            $extensions = $extensions ?: ['*.*'];

            $extensionFilters[] = ['description' => $this->filterName ?: $this->filterExtensions, 'extensions' => $extensions];

        }

        if ($this->filterAny && $this->filterExtensions != '*.*') {
            $extensionFilters[] = [
                'description' => 'All files (*.*)',
                'extensions'  => ['*.*']
            ];
        }

        $this->_dialog->extensionFilters = $extensionFilters;
        $this->_dialog->initialDirectory = $this->initialDirectory;
        $this->_dialog->initialFileName = $this->initialFileName;

        if ($this->multiple) {
            $file = $this->saveDialog ? $this->_dialog->showSaveDialog() : $this->_dialog->showOpenMultipleDialog();
        } else {
            $file = $this->saveDialog ? $this->_dialog->showSaveDialog() : $this->_dialog->showOpenDialog();
        }

        if ($file !== null) {
            $this->file = is_array($file) ? arr::first($file) : $file;
            $this->files = is_array($file) ? $file : [$file];

            $this->initialDirectory = fs::parent($this->file);

            $this->_adaptValue($this->inputNode, $this->files);

            $this->trigger('action');
            return $this->multiple ? $this->files : $this->file;
        } else {
            $this->file = null;
            $this->files = [];

            $this->trigger('cancel');
            return null;
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