<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use ide\Ide;
use ide\project\Project;
use php\gui\framework\DataUtils;
use php\gui\UXApplication;
use php\gui\UXImage;
use php\gui\UXImageView;
use php\gui\UXListView;
use php\gui\UXNode;
use php\io\Stream;

/**
 * Class ImageViewFormElement
 * @package ide\formats\form
 */
class ImageViewFormElement extends AbstractFormElement
{
    public function getName()
    {
        return 'Изображение';
    }

    public function getIcon()
    {
        return 'icons/image16.png';
    }

    public function getIdPattern()
    {
        return "image%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $image = new UXImageView();
        $image->preserveRatio = false;

        return $image;
    }

    public function getDefaultSize()
    {
        return [150, 100];
    }

    public function registerNode(UXNode $node)
    {
        /** @var UXImageView $node */
        $data = DataUtils::get($node);
        $image = $data->get('image');

        UXApplication::runLater(function () use ($image, $node) {
            if ($image) {
                $file = Ide::get()->getOpenedProject()->getFile("src/$image");

                if ($file->exists()) {
                    $node->image = new UXImage($file);
                }
            }

            if (!$node->image) {
                $node->image = Ide::get()->getImage('dummyImage.png')->image;
            }
        });
    }

    public function isOrigin($any)
    {
        return $any instanceof UXImageView;
    }
}
