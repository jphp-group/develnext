<?php
namespace ide\formats\form\context;

use Exception;
use ide\editors\AbstractEditor;
use ide\editors\form\IdeTabPane;
use ide\editors\FormEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\event\AbstractEventKind;
use ide\Logger;
use ide\ui\Notifications;
use php\format\ProcessorException;
use php\gui\framework\behaviour\custom\BehaviourLoader;
use php\gui\framework\DataUtils;
use php\gui\UXClipboard;
use php\gui\UXCustomNode;
use php\gui\UXDialog;
use php\gui\UXLoader;
use php\gui\UXMenuItem;
use php\gui\UXNode;
use php\io\MemoryStream;
use php\io\Stream;
use php\lib\arr;
use php\lib\Items;
use php\lib\reflect;
use php\lib\str;
use php\xml\DomElement;
use php\xml\DomNode;
use php\xml\DomNodeList;
use php\xml\XmlProcessor;

class PasteMenuCommand extends AbstractMenuCommand
{
    public function getName()
    {
        return 'Вставить';
    }

    public function getAccelerator()
    {
        return 'Ctrl+V';
    }

    public function getIcon()
    {
        return 'icons/paste16.png';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        /** @var FormEditor $editor */

        if (UXClipboard::hasText()) {
            $text = UXClipboard::getText();

            $processor = new XmlProcessor();

            $selectedNode = Items::first($editor->getDesigner()->getSelectedNodes());
            /** @var AbstractFormElement $selectedType */
            $selectedType = $selectedNode ? $editor->getFormat()->getFormElement($selectedNode) : null;

            $editor->getDesigner()->unselectAll();
            $allCode = '';

            try {
                $document = $processor->parse($text);
                $imports = $document->findAll("/processing-instruction('import')");

                $rootElement = $document->getDocumentElement();
                $count = (int) $rootElement->getAttribute('count');

                $nodes = $document->findAll('/copies/node');

                /** @var DomElement $behaviours */
                $behaviours = $document->find('/copies/behaviours');

                $addLayout = function (UXNode $uiNode, $factoryId) use ($editor, &$addLayout, $document, $behaviours, &$allCode) {
                    /** @var AbstractFormElement $type */
                    $type = $editor->getFormat()->getFormElement($uiNode);

                    if ($type) {
                        if ($oldId = $uiNode->data('old-id')) {
                            $id = $uiNode->id;
                        } else {
                            $oldId = $editor->getNodeId($uiNode);
                            $id = $editor->generateNodeId($type, $oldId);
                        }

                        if (!$factoryId) {
                            $uiNode->id = $id;
                        }

                        /** @var DomElement $oneDataElement */
                        $oneDataElement = $document->find("/copies/data/one[@id='$oldId']");
                        $oneBindElements = $document->findAll("/copies/binds/bind[@id='$oldId']");

                        if ($oneBindElements->count()) {
                            Logger::debug("Paste events (count = {$oneBindElements->count()}).");

                            /** @var DomElement $oneBindElement */
                            foreach ($oneBindElements as $oneBindElement) {
                                $event = $oneBindElement->getAttribute('event');
                                $methodName = $oneBindElement->getAttribute('methodName');

                                $code = $oneBindElement->find('./code');
                                $actions = $oneBindElement->findAll('./actions/*');

                                if ($code) {
                                    $code = $code->getTextContent();
                                }

                                if (!$editor->getEventManager()->findBind($id, $event)) {
                                    $eventType = $type->findEventType($event);

                                    /** @var AbstractEventKind $eventKind */
                                    $eventKind = $eventType['kind'];

                                    if ($eventKind instanceof AbstractEventKind) {
                                        $editor->getEventManager()->addBind($id, $event, $eventKind);
                                        $editor->getEventManager()->load();

                                        $bind = $editor->getEventManager()->findBind($id, $event);

                                        if ($bind) {
                                            if ($code) {
                                                $editor->getEventManager()->replaceCodeOfMethod($bind['className'], $bind['methodName'], $code);
                                                $editor->getEventManager()->load();

                                                $allCode .= "$code\n\n";
                                            }

                                            if ($actions->count()) {
                                                /** @var DomElement $one */
                                                foreach ($actions as $one) {
                                                    $action = $editor->getActionEditor()->getManager()->buildAction($one);

                                                    if ($action) {
                                                        $editor->getActionEditor()->addAction($action, $bind['className'], $bind['methodName']);
                                                        Logger::debug("Add action " . reflect::typeOf($action->getType()));
                                                    } else {
                                                        Logger::warn("Unable to create action from '{$one->getTagName()}'");
                                                    }
                                                }
                                            } else {
                                                Logger::debug("Paste empty action list.");
                                            }
                                        }

                                    } else {
                                        Logger::warn("Unable to paste event '$id.$event', event kind not found.");
                                    }
                                } else {
                                    Logger::warn("Unable to paste event '$id.$event', already exists!");
                                }
                            }
                        }

                        // Paste virtual properties
                        if ($oneDataElement) {
                            $data = DataUtils::get($uiNode);

                            foreach ($oneDataElement->getAttributes() as $name => $value) {
                                if ($name == "id") continue;

                                $data->set($name, $value);
                            }
                        }

                        if ($type->isLayout()) {
                            foreach ($type->getLayoutChildren($uiNode) as $sub) {
                                $editor->registerNode($sub);
                                $addLayout($sub, $factoryId);
                            }
                        }

                        if ($behaviours && !$factoryId) {
                            BehaviourLoader::loadOne($oldId, $behaviours, $editor->getBehaviourManager(), $id);
                        }

                        $editor->refreshNode($uiNode);
                    }
                };

                /** @var DomElement $element */
                foreach ($nodes as $one) {
                    $element = $one->find("./*");

                    $uiNode = null;
                    $targetId = null;

                    if ($factoryId = $one->getAttribute('factoryId')) {
                        $uiNode = $editor->createClone($factoryId);

                        if ($uiNode) {
                            $loader = new UXLoader();
                            $customNode = $loader->load($this->makeXmlForLoader($element, $imports));

                            if ($customNode instanceof UXCustomNode) {
                                $uiNode->x = $customNode->get('x');
                                $uiNode->y = $customNode->get('y');
                            }
                        } else {
                            continue;
                        }
                    }

                    if ($uiNode == null) {
                        $loader = new UXLoader();
                        $uiNode = $loader->load($this->makeXmlForLoader($element, $imports));
                    }

                    $type = $editor->getFormat()->getFormElement($uiNode);

                    if (!$type) {
                        continue;
                    }

                    $offsetX = $editor->getDesigner()->snapSizeX * ($count + 1);
                    $offsetY = $editor->getDesigner()->snapSizeY * ($count + 1);

                    $uiNode->x += $offsetX;
                    $uiNode->y += $offsetY;

                    $busyIds = [];

                    $editor->eachNode(function (UXNode $node, $nodeId, AbstractFormElement $type) use ($editor, &$busyIds) {
                        if ($nodeId) {
                            $node->data('old-id', $nodeId);
                            $node->id = $editor->generateNodeId($type, $nodeId, $busyIds);
                            $busyIds[$node->id] = $node->id;
                        }
                    }, [$uiNode]);

                    if ($selectedType && $selectedType->isLayout()) {
                        $selectedType->addToLayout($selectedNode, $uiNode, $selectedNode->screenX + $offsetX, $selectedNode->screenY + $offsetY);
                    } else {
                        $editor->getLayout()->add($uiNode);
                    }

                    $editor->registerNode($uiNode);
                    $addLayout($uiNode, $factoryId);

                    $editor->getCodeEditor()->loadContentToAreaIfModified();
                    $editor->getAutoComplete()->pasteUsesFromCode($allCode);

                    waitAsync(100, function () use ($editor, $uiNode) {
                        $editor->getDesigner()->selectNode($uiNode);
                    });
                }

                $editor->getDesigner()->update();

                $leftPaneUi = $editor->getLeftPaneUi();

                if ($leftPaneUi instanceof IdeTabPane) {
                    $leftPaneUi->refreshObjectTreeList();
                }

                if ($count >= 0) {
                    $rootElement->setAttribute('count', $count + 1);
                }

                if ($editor) {
                    $editor->reindex();
                }

                UXClipboard::setText($processor->format($document));
            } catch (ProcessorException $e) {
                Notifications::warning('Невозможно вставить', 'Невозможно вставить скопированную информацию как объекты DevelNext!');
                return;
            }
        } else {
            Notifications::warning('Невозможно вставить', 'Ваш буфер обмена пуст, нет скопированной информации для вставки объектов!');
        }
    }

    /**
     * @param DomNode $node
     * @param DomNodeList $imports
     *
     * @return Stream
     */
    protected function makeXmlForLoader(DomNode $node, $imports)
    {
        $processor = new XmlProcessor();
        $document = $processor->createDocument();

        $cloneNode = $document->importNode($node, true);

        $document->appendChild($cloneNode);

        foreach ($imports as $import) {
            $cloneImport = $import->cloneNode(true);
            $document->adoptNode($cloneImport);

            $document->insertBefore($cloneImport, $document->getDocumentElement());
        }

        $result = new MemoryStream();

        $processor->formatTo($document, $result);
        $result->seek(0);

        return $result;
    }
}