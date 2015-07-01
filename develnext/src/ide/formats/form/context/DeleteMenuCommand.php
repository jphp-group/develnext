<?php
namespace ide\formats\form\context {

    use ide\editors\AbstractEditor;
    use ide\editors\FormEditor;
    use ide\editors\menu\AbstractMenuCommand;
    use ide\Ide;
    use php\gui\event\UXKeyEvent;
    use php\gui\framework\DataUtils;
    use php\gui\UXDialog;
    use php\lib\Items;

    class DeleteMenuCommand extends AbstractMenuCommand
    {
        public function getName()
        {
            return 'Удалить';
        }

        public function getAccelerator()
        {
            return 'delete';
        }

        public function getIcon()
        {
            return Ide::get()->getImage('icons/delete16.png');
        }

        public function withSeparator()
        {
            return true;
        }

        public function onExecute($e, AbstractEditor $editor)
        {
            /** @var FormEditor $editor */
            $designer = $editor->getDesigner();

            $nodes = $designer->getSelectedNodes();

            foreach ($nodes as $node) {
                $designer->unselectNode($node);
                $designer->unregisterNode($node);

                DataUtils::remove($node);
                $node->parent->remove($node);
            }

            $nodes = Items::toList($designer->getNodes());

            if ($node = $nodes[sizeof($nodes) - 1]) {
                $designer->selectNode($node);
            }
        }
    }

}