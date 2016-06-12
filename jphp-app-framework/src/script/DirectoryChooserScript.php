<?php
namespace script;

use php\gui\framework\AbstractScript;
use php\gui\framework\Application;
use php\gui\framework\behaviour\TextableBehaviour;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXColorChooser;
use php\gui\UXDialog;
use php\gui\UXDirectoryChooser;
use php\gui\UXFileChooser;
use php\gui\UXNode;
use php\io\File;
use php\lang\InterruptedException;
use php\lang\Thread;
use php\lib\Items;
use php\xml\DomDocument;
use script\support\ScriptHelpers;

/**
 * Class DirectoryChooserScript
 * @package script
 */
class DirectoryChooserScript extends AbstractScript implements TextableBehaviour
{
    use ScriptHelpers;

    /**
     * @var UXDirectoryChooser
     */
    protected $_dialog;

    /**
     * @var File
     */
    public $file;

    /**
     * @var string
     */
    public $inputNode;

    /**
     * @var string
     */
    public $actionNode;

    function __construct()
    {
        $this->_dialog = new UXDirectoryChooser();
    }

    /**
     * @return File
     */
    public function execute()
    {
        $file = $this->_dialog->showDialog();

        if ($file !== null) {
            $this->file = $file;

            $this->_adaptValue($this->inputNode, $this->file);

            $this->trigger('action');
            return $file;
        } else {
            $this->file = null;

            $this->trigger('cancel');
        }

        return null;
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