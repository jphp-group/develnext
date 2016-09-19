<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\AbstractFormElementTag;
use php\gui\framework\DataUtils;
use php\gui\layout\UXAnchorPane;
use php\gui\UXDialog;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\gui\UXTitledPane;
use php\lib\Str;
use php\lib\String;
use php\xml\DomDocument;
use php\xml\DomElement;

class TabPaneFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'TabPaneEx';
    }

    public function getElementClass()
    {
        return UXTabPane::class;
    }

    public function writeContent($node, DomElement $element, DomDocument $document, AbstractFormDumper $dumper)
    {
        /** @var UXTabPane $node */
        if ($node->tabs->count) {
            $domTabs = $document->createElement('tabs');

            $element->appendChild($domTabs);

            $data = DataUtils::get($node);

            /** @var UXTab $tab */
            foreach ($node->tabs as $i => $tab) {
                $domTab = $document->createElement('Tab');

                $domTabs->appendChild($domTab);

                $domTab->setAttribute('text', $tab->text);

                if (!$tab->closable) {
                    $domTab->setAttribute('closable', 'false');
                }

                if ($tab->content) {
                    $domContent = $document->createElement('content');
                    $domTab->appendChild($domContent);

                    $domContentSub = $dumper->createElementTag(null, $tab->content, $document, false);
                    $domContent->appendChild($domContentSub);
                }
            }
        }
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXTabPane $node */
        $element->setAttribute('tabClosingPolicy', $node->tabClosingPolicy);

        if ($node->side != 'TOP') {
            $element->setAttribute('side', $node->side);
        }
    }
}