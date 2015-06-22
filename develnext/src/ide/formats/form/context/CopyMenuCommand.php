<?php
namespace ide\formats\form\context;

use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\Ide;
use php\gui\UXClipboard;
use php\xml\XmlProcessor;

/**
 * Class CopyMenuCommand
 * @package ide\formats\form\context
 */
class CopyMenuCommand extends AbstractMenuCommand
{
    public function getName()
    {
        return 'Копировать';
    }

    public function getAccelerator()
    {
        return 'Ctrl+C';
    }

    public function getIcon()
    {
        return 'icons/copy16.png';
    }

    public function onExecute($e, AbstractEditor $editor, $disableCount = false)
    {
        /** @var FormEditor $editor */
        $designer = $editor->getDesigner();
        $nodes = $designer->getSelectedNodes();

        if ($nodes) {
            $processor = new XmlProcessor();
            $document = $processor->createDocument();

            $rootElement = $document->createElement('copies');

            if ($disableCount) {
                $rootElement->setAttribute('count', -1);
            } else {
                $rootElement->setAttribute('count', 0);
            }

            $rootElement->setAttribute('ideName', Ide::get()->getName());
            $rootElement->setAttribute('ideVersion', Ide::get()->getVersion());
            $rootElement->setAttribute('ideNamespace', Ide::get()->getNamespace());

            $document->appendChild($rootElement);

            foreach ($nodes as $node) {
                $nodeElement = $editor->getFormDumper()->createElementTag($node, $document);

                if ($nodeElement != null) {
                    $wrapElement = $document->createElement('node');
                    $wrapElement->appendChild($nodeElement);

                    $rootElement->appendChild($wrapElement);
                }
            }

            $editor->getFormDumper()->appendImports($nodes, $document);

            UXClipboard::setText($processor->format($document));
        }
    }
}