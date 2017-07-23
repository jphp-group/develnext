<?php
namespace ide\formats\form\elements;

use develnext\lexer\inspector\entry\TypeEntry;
use ide\formats\form\AbstractFormElement;
use ide\library\IdeLibraryScriptGeneratorResource;
use ide\systems\Cache;
use php\gui\designer\UXDesigner;
use php\gui\UXCanvas;
use php\gui\UXListView;
use php\gui\UXNode;
use php\gui\UXRadioGroupPane;
use php\lib\reflect;

/**
 * @package ide\formats\form
 */
class CanvasFormElement extends AbstractFormElement
{
    public function getName()
    {
        return 'Канвас';
    }

    public function getGroup()
    {
        return 'Дополнительно';
    }

    public function getElementClass()
    {
        return UXCanvas::class;
    }

    public function getIcon()
    {
        return 'icons/paintBrush16.png';
    }

    public function getIdPattern()
    {
        return "canvas%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        return new UXCanvas();
    }

    public function getDefaultSize()
    {
        return [300, 300];
    }

    public function isOrigin($any)
    {
        return reflect::typeOf($any) === UXCanvas::class;
    }

    public function designHasBeenChanged(UXNode $node, UXDesigner $designer)
    {
        /** @var UXCanvas $node */

        $gc = $node->gc();

        $w = $node->width;
        $h = $node->height;

        $gc->clearRect(0, 0, $w, $h);

        $image = Cache::getResourceImage('.data/img/transparent.png');

        for ($i = 0; $i < ceil($w / $image->width); $i++) {
            for ($j = 0; $j < ceil($h / $image->height); $j++) {
                $gc->drawImage($image, $i * $image->width, $j * $image->height);
            }
        }
    }
    
    public function refreshNode(UXNode $node, UXDesigner $designer)
    {
        $this->designHasBeenChanged($node, $designer);
    }

    public function getScriptGenerators()
    {
        return [
            new IdeLibraryScriptGeneratorResource('res://.dn/bundle/uiDesktop/scriptgen/DrawLineCanvasScriptGen'),
            new IdeLibraryScriptGeneratorResource('res://.dn/bundle/uiDesktop/scriptgen/DrawSVGCanvasScriptGen'),
            new IdeLibraryScriptGeneratorResource('res://.dn/bundle/uiDesktop/scriptgen/SaveImageToFileCanvasScriptGen'),
        ];
    }
}
