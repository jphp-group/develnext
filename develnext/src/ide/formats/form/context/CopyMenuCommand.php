<?php
namespace ide\formats\form\context;

use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\formats\AbstractFormFormat;
use ide\formats\form\AbstractFormElement;
use ide\Ide;
use ide\Logger;
use php\gui\framework\DataUtils;
use php\gui\UXClipboard;
use php\gui\UXMenuItem;
use php\gui\UXNode;
use php\lib\items;
use php\lib\reflect;
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
            $bindsElement = $document->createElement('binds');
            $rootElement->appendChild($dataElement);
            $rootElement->appendChild($bindsElement);

            $targetIds = [];


            $editor->eachNode(function (UXNode $node) use (&$targetIds, $editor, $document, $dataElement, $bindsElement) {
                $id = $editor->getNodeId($node);

                if ($id) {
                    $oneElement = $document->createElement('one');
                    $data = DataUtils::get($node);

                    if ($data) {
                        $oneElement->setAttributes($data->toArray());
                        $oneElement->setAttribute('id', $id);

                        $dataElement->appendChild($oneElement);
                    }

                    $eventManager = $editor->getEventManager();

                    if ($binds = $eventManager->findBinds($id)) {
                        foreach ($binds as $event => $bind) {
                            $bindElement = $document->createElement('bind');
                            $bindElement->setAttribute('id', $id);
                            $bindElement->setAttribute('event', $event);
                            $bindElement->setAttribute('methodName', $bind['methodName']);

                            if ($actions = $editor->getActionEditor()->findMethod($bind['className'], $bind['methodName'])) {
                                $actionsElement = $document->createElement('actions');

                                foreach ($actions as $action) {
                                    $actionElement = $editor->getActionEditor()->makeActionDom($action, $document);
                                    $actionsElement->appendChild($actionElement);
                                }

                                $bindElement->appendChild($actionsElement);
                            }

                            $codeElement = $document->createElement('code');
                            $codeElement->setTextContent($eventManager->getCodeOfMethod($bind['className'], $bind['methodName']));
                            $bindElement->appendChild($codeElement);

                            $bindsElement->appendChild($bindElement);
                        }
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
                $nodeElement = $editor->getFormDumper()->createElementTag($editor, $node, $document);

                if ($nodeElement != null) {
                    $wrapElement = $document->createElement('node');

                    if ($node instanceof UXNode && $node->data('-factory-id')) {
                        $wrapElement->setAttribute('factoryId', $node->data('-factory-id'));
                    }

                    $wrapElement->appendChild($nodeElement);

                    //$wrapElement->setAttributes(DataUtils::get($node)->toArray());

                    $rootElement->appendChild($wrapElement);
                } else {
                    Logger::error("Unable to copy " . reflect::typeOf($node) . ", cannot create element tag");
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