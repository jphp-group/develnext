<?php
namespace ide\formats\form\context {

    use ide\editors\AbstractEditor;
    use ide\editors\FormEditor;
    use ide\editors\menu\AbstractMenuCommand;
    use ide\Ide;
    use php\gui\event\UXKeyEvent;
    use php\gui\framework\DataUtils;
    use php\gui\UXDialog;
    use php\gui\UXMenuItem;
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

        public function onExecute($e = null, AbstractEditor $editor = null)
        {
            /** @var FormEditor $editor */
            $designer = $editor->getDesigner();

            $nodes = $designer->getSelectedNodes();

            foreach ($nodes as $node) {
                $editor->deleteNode($node);
            }

            $nodes = Items::toList($designer->getNodes());

            if ($node = $nodes[sizeof($nodes) - 1]) {
                if ($node) {
                    $designer->selectNode($node);
                } else {
                    $editor->selectForm();
                }
            } else {
                $editor->selectForm();
            }
        }

        public function onBeforeShow(UXMenuItem $item, AbstractEditor $editor = null)
        {
            /** @var FormEditor $editor */
            $item->disable = !items::first($editor->getDesigner()->getSelectedNodes());
        }
    }
}