<?php
namespace ide\formats\form\elements;

use ide\editors\value\BooleanPropertyEditor;
use ide\editors\value\ColorPropertyEditor;
use ide\editors\value\FontPropertyEditor;
use ide\editors\value\IntegerPropertyEditor;
use ide\editors\value\PositionPropertyEditor;
use ide\editors\value\SimpleTextPropertyEditor;
use ide\editors\value\TextPropertyEditor;
use ide\formats\form\AbstractFormElement;
use php\gui\designer\UXDesignProperties;
use php\gui\designer\UXDesignPropertyEditor;
use php\gui\effect\UXInnerShadowEffect;
use php\gui\framework\DataUtils;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXPanel;
use php\gui\shape\UXRectangle;
use php\gui\UXButton;
use php\gui\UXLabel;
use php\gui\UXMediaView;
use php\gui\UXMediaViewBox;
use php\gui\UXNode;
use php\gui\UXTableCell;
use php\gui\UXTextField;
use php\gui\UXTitledPane;

/**
 * Class ButtonFormElement
 * @package ide\formats\form
 */
class MediaViewFormElement extends AbstractFormElement
{
    public function getElementClass()
    {
        return UXMediaViewBox::class;
    }

    public function getName()
    {
        return 'Видео плеер';
    }

    public function getIcon()
    {
        return 'icons/tv16.png';
    }

    public function getIdPattern()
    {
        return "mediaView%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $panel = new UXMediaViewBox();
        return $panel;
    }

    public function getDefaultSize()
    {
        return [300, 200];
    }

    public function isOrigin($any)
    {
        return get_class($any) == UXMediaViewBox::class;
    }
}
