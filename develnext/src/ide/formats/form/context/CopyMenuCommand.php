<?php
namespace ide\formats\form\context;

use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\formats\AbstractFormFormat;
use ide\formats\form\AbstractFormElement;
use ide\Ide;
use php\gui\framework\DataUtils;
use php\gui\UXClipboard;
use php\gui\UXMenuItem;
use php\gui\UXNode;
use php\lib\items;
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

    public function onExecute($e = null, AbstractEditor $editor = null, $disableCount = false)
    {
        /** @var FormEditor $editor */
        $designer = $editor->getDesigner();
        $nodes = $designer->getSelectedNodes();

        /** @var AbstractFormFormat $format */
        $format = $editor->getFormat();

        if ($nodes) {
            $needNodes = [];

            // фильтруем ноды для копирования, убираем дубликаты.
            foreach ($nodes as $node) {
                $need = true;

                if ($node->id) {
                    foreach ($nodes as $one) {
                        if ($one === $node) continue;

                        if ($one->lookup("#$node->id")) {
                            $need = false;
                            break;
                        }
                    }
                }

                if ($need) {
                    $needNodes[] = $node;
                }
            }

            $nodes = $needNodes;

            if (!$nodes) {
                return;
            }

            $processor = new XmlProcessor();
            $document = $processor->createDocument();

            $rootElement = $document->createElement('copies');
            $dataElement = $document->createElement('data');
            $rootElement->appendChild($dataElement);

            $targetIds = [];


            $editor->eachNode(function (UXNode $node) use (&$targetIds, $editor, $document, $dataElement) {
                $id = $editor->getNodeId($node);

                if ($id) {
                    $oneElement = $document->createElement('one');
                    $data = DataUtils::get($node);

                    if ($data) {
                        $oneElement->setAttributes($data->toArray());
                        $oneElement->setAttribute('id', $id);

                        $dataElement->appendChild($oneElement);
                    }

                    $targetIds[] = $id;
                }
            }, $nodes);

            if ($targetIds) {
                $behaviourElement = $editor->getBehaviourManager()->dump($document, $targetIds);
                $rootElement->appendChild($behaviourElement);
            }

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

                    if ($node instanceof UXNode && $node->data('-factory-id')) {
                        $wrapElement->setAttribute('factoryId', $node->data('-factory-id'));
                    }

                    $wrapElement->appendChild($nodeElement);

                    //$wrapElement->setAttributes(DataUtils::get($node)->toArray());

                    $rootElement->appendChild($wrapElement);
                }
            }

            $editor->getFormDumper()->appendImports($nodes, $document);

            UXClipboard::setText($processor->format($document));
        }
    }

    public function onBeforeShow(UXMenuItem $item, AbstractEditor $editor = null)
    {
        /** @var FormEditor $editor */
        $item->disable = !items::first($editor->getDesigner()->getSelectedNodes());
    }
}