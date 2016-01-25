<?php
namespace ide\formats\form\context;

use Exception;
use ide\editors\AbstractEditor;
use ide\editors\form\IdeTabPane;
use ide\editors\FormEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\formats\form\AbstractFormElement;
use php\format\ProcessorException;
use php\gui\framework\behaviour\custom\BehaviourLoader;
use php\gui\framework\DataUtils;
use php\gui\framework\Timer;
use php\gui\UXClipboard;
use php\gui\UXDialog;
use php\gui\UXLoader;
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

                $addLayout = function (UXNode $uiNode) use ($editor, &$addLayout) {
                    $type = $editor->getFormat()->getFormElement($uiNode);

                    if ($type) {
                        $uiNode->id = $editor->generateNodeId($type);

                        if ($type->isLayout()) {
                            foreach ($type->getLayoutChildren($uiNode) as $sub) {
                                $editor->registerNode($sub);

                                $addLayout($sub);
                            }
                        }
                    }
                };

                /** @var DomElement $element */
                foreach ($nodes as $one) {
                    $element = $one->find("./*");

                    $loader = new UXLoader();
                    $uiNode = $loader->load($this->makeXmlForLoader($element, $imports));

                    $type = $editor->getFormat()->getFormElement($uiNode);

                    if (!$type) {
                        continue;
                    }

                    $targetId = $editor->getNodeId($uiNode);

                    $offset = $editor->getDesigner()->snapSize * ($count + 1);

                    $uiNode->x += $offset;
                    $uiNode->y += $offset;

                    if ($selectedType && $selectedType->isLayout()) {
                        $selectedType->addToLayout($selectedNode, $uiNode, $selectedNode->screenX + $offset, $selectedNode->screenY + $offset);
                    } else {
                        $editor->getLayout()->add($uiNode);
                    }

                    $editor->registerNode($uiNode);
                    $addLayout($uiNode);

                    // Paste virtual properties
                    $data = DataUtils::get($uiNode);
                    foreach ($one->getAttributes() as $name => $value) {
                        if ($name == "id") continue;

                        $data->set($name, $value);
                    }

                    $type->refreshNode($uiNode);

                    Timer::run(100, function () use ($editor, $uiNode, $one, $data) {
                        $editor->getDesigner()->selectNode($uiNode);
                    });

                    if ($behaviours) {
                        BehaviourLoader::loadOne($targetId, $behaviours, $editor->getBehaviourManager(), $editor->getNodeId($uiNode));
                    }
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
                return;
            }
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