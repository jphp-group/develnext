<?php
namespace ide\webplatform\formats\form;

use framework\web\ui\UIButton;
use framework\web\ui\UIHyperlink;
use php\gui\UXButton;
use php\gui\UXHyperlink;
use php\gui\UXNode;
use php\lib\num;
use php\lib\str;

class HyperlinkWebElement extends LabeledWebElement
{
    public function getElementClass()
    {
        return UIHyperlink::class;
    }

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'Hyperlink';
    }

    public function uiStylesheets(): array
    {
        return [
        ];
    }

    public function loadUiSchema(UXNode $view, array $uiSchema)
    {
        /** @var UXButton $view */
        parent::loadUiSchema($view, $uiSchema);

        $view->data('href', $uiSchema['href']);
        $view->data('target', $uiSchema['target'] ?: '_self');
    }

    public function uiSchema(UXNode $view): array
    {
        /** @var UXButton $view */
        $schema = parent::uiSchema($view);

        if ($view->data('href')) {
            $schema['href'] = $view->data('href');
        }

        if ($view->data('target') && $view->data('target') !== '_self') {
            $schema['target'] = $view->data('target');
        }

        if ($schema['font']['underline']) {
            unset($schema['font']['underline']);
        }

        return $schema;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Ссылка';
    }

    public function getIcon()
    {
        return 'icons/hyperlink16.png';
    }

    public function getIdPattern()
    {
        return "link%s";
    }

    public function getDefaultSize()
    {
        return [100, 20];
    }

    /**
     * @return UXNode
     */
    public function createViewElement(): UXNode
    {
        $view = new UXHyperlink($this->getName());
        $view->font->size = $this->getDefaultFontSize();
        $view->classes->add('ux-hyperlink');
        return $view;
    }
}