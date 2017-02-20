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
use ide\systems\Cache;
use php\gui\designer\UXDesignProperties;
use php\gui\designer\UXDesignPropertyEditor;
use php\gui\event\UXDragEvent;
use php\gui\framework\DataUtils;
use php\gui\layout\UXHBox;
use php\gui\UXButton;
use php\gui\UXCheckbox;
use php\gui\UXDragboard;
use php\gui\UXImage;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\gui\UXLabeled;
use php\gui\UXNode;
use php\gui\UXTableCell;
use php\gui\UXTextField;
use php\lib\fs;

abstract class LabeledFormElement extends AbstractFormElement
{
    public function getElementClass()
    {
        return UXLabeled::class;
    }

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

    private function getFileFromDragDrop(UXDragboard $db)
    {
        foreach ($db->files as $file) {
            $ext = fs::ext($file);

            switch ($ext) {
                case 'png':
                case 'jpeg':
                case 'jpg':
                case 'gif':
                    return $file;
            }
        }

        return null;
    }

    public function canDragDropIn(UXDragEvent $e)
    {
        return (bool) $this->getFileFromDragDrop($e->dragboard);
    }

    public function dragDropIn(UXDragEvent $e, UXNode $node)
    {
        /** @var UXLabeled $node */
        $file = $this->getFileFromDragDrop($e->dragboard);

        if ($project = Ide::project()) {
            $imgFile = null;

            if ($duplicates = $project->findDuplicatedFiles($file)) {
                $imgFile = $duplicates[0];
            } else {
                $imgFile = $project->copyFile($file, 'src/.data/img/icons');
            }

            $node->graphic = $icon = new UXImageView(Cache::getImage($imgFile));

            $data = DataUtils::get($node);
            $data->set('graphic', $imgFile->getSrcRelativePath());
        }
    }
}
