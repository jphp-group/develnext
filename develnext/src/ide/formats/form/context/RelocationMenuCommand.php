<?php
namespace ide\formats\form\context;

use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use ide\editors\menu\AbstractMenuCommand;
use php\lang\Invoker;

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

        $size = $designer->snapEnabled ? $designer->snapSize : 1;

        foreach ($designer->getSelectedNodes() as $node) {
            if ($designer->getNodeLock($node)) {
                continue;
            }

            $this->relocateHandler->call($node, $size);
        }

        $designer->update();
    }
}