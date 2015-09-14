<?php
namespace script;

use php\gui\framework\AbstractScript;
use php\gui\framework\behaviour\TextableBehaviour;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\gui\UXFileChooser;
use php\gui\UXNode;
use php\io\File;
use php\lang\InterruptedException;
use php\lang\Thread;
use php\lib\Items;
use php\xml\DomDocument;
use script\support\ScriptHelpers;

/**
 * Class TimerScript
 * @package script
 */
class FileChooserScript extends AbstractScript implements TextableBehaviour
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
        if ($this->multiple) {
            $file = $this->saveDialog ? $this->_dialog->showSaveDialog() : $this->_dialog->showOpenMultipleDialog();
        } else {
            $file = $this->_dialog->showOpenDialog();
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
}