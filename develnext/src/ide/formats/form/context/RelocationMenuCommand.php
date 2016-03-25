<?php
namespace ide\formats\form\context;

use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use ide\editors\menu\AbstractMenuCommand;
use php\lang\Invoker;
use php\lib\str;

class RelocationMenuCommand extends AbstractMenuCommand
{
    /**
     * @var string
     */
    private $accelerator;

    /**
     * @var Invoker
     */
    private $relocateHandler;

    private $id;

    /**
     * RelocationMenuCommand constructor.
     *
     * @param $accelerator
     * @param callable $relocateHandler
     */
    public function __construct($accelerator, callable $relocateHandler)
    {
        $this->accelerator = $accelerator;
        $this->relocateHandler = Invoker::of($relocateHandler);
        $this->id = str::uuid();
    }

    public function getUniqueId()
    {
        return parent::getUniqueId() . $this->id;
    }

    public function isHidden()
    {
        return true;
    }

    public function getName()
    {
        return '';
    }

    public function getAccelerator()
    {
        return $this->accelerator;
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        /** @var FormEditor $editor */
        $designer = $editor->getDesigner();

        $sizeX = $designer->snapEnabled ? $designer->snapSizeX : 1;
        $sizeY = $designer->snapEnabled ? $designer->snapSizeY : 1;

        foreach ($designer->getSelectedNodes() as $node) {
            if ($designer->getNodeLock($node)) {
                continue;
            }

            $this->relocateHandler->call($node, $sizeX, $sizeY);
        }

        $designer->update();
    }
}