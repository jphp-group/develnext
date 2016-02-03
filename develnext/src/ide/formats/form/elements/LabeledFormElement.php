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
use ide\Ide;
use php\gui\designer\UXDesignProperties;
use php\gui\designer\UXDesignPropertyEditor;
use php\gui\framework\DataUtils;
use php\gui\layout\UXHBox;
use php\gui\UXButton;
use php\gui\UXCheckbox;
use php\gui\UXImage;
use php\gui\UXImageView;
use php\gui\UXLabeled;
use php\gui\UXNode;
use php\gui\UXTableCell;
use php\gui\UXTextField;

abstract class LabeledFormElement extends AbstractFormElement
{
    public function registerNode(UXNode $node)
    {
        parent::registerNode($node);

        /** @var UXLabeled $node */
        $data = DataUtils::get($node);

        $image = $data->get('graphic');

        if ($image) {
            $file = Ide::get()->getOpenedProject()->getFile("src/$image");

            if ($file->exists()) {
                $graphic = new UXImageView();
                $graphic->image = new UXImage($file);

                $node->graphic = $graphic;
            }
        }
    }
}
