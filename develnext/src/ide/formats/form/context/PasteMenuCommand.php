<?php
namespace ide\formats\form\context;

use Exception;
use ide\editors\AbstractEditor;
use ide\editors\form\IdeTabPane;
use ide\editors\FormEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\formats\form\AbstractFormElement;
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
use php\lib\Items;
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

            try {
                $document = $processor->parse($text);
                $imports = $document->findAll("/processing-instruction('import')");

                $rootElement = $document->getDocumentElement();
                $count = (int) $rootElement->getAttribute('count');

                $nodes = $document->findAll('/copies/node');

                /** @var DomElement $behaviours */
                $behaviours = $document->find('/copies/behaviours');

                $addLayout = function (UXNode $uiNode, $factoryId) use ($editor, &$addLayout, $document, $behaviours) {
                    /** @var AbstractFormElement $type */
                    $type = $editor->getFormat()->getFormElement($uiNode);

                    if ($type) {
                        $oldId = $editor->getNodeId($uiNode);
                        $id = $editor->generateNodeId($type);

                        if (!$factoryId) {
                            $uiNode->id = $id;
                        }

                        /** @var DomElement $oneDataElement */
                        $oneDataElement = $document->find("/copies/data/one[@id='$oldId']");

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

                        $type->refreshNode($uiNode);

                        if ($behaviours && !$factoryId) {
                            BehaviourLoader::loadOne($oldId, $behaviours, $editor->getBehaviourManager(), $id);
                        }
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

                        $targetId = $editor->getNodeId($uiNode);
                    }

                    $type = $editor->getFormat()->getFormElement($uiNode);

                    if (!$type) {
                        continue;
                    }

                    $offsetX = $editor->getDesigner()->snapSizeX * ($count + 1);
                    $offsetY = $editor->getDesigner()->snapSizeY * ($count + 1);

                    $uiNode->x += $offsetX;
                    $uiNode->y += $offsetY;

                    if ($selectedType && $selectedType->isLayout()) {
                        $selectedType->addToLayout($selectedNode, $uiNode, $selectedNode->screenX + $offsetX, $selectedNode->screenY + $offsetY);
                    } else {
                        $editor->getLayout()->add($uiNode);
                    }

                    $editor->registerNode($uiNode);
                    $addLayout($uiNode, $factoryId);

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