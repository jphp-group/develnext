<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use ide\Ide;
use ide\project\Project;
use php\gui\framework\DataUtils;
use php\gui\UXApplication;
use php\gui\UXImage;
use php\gui\UXImageArea;
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
        $image = new UXImageArea();
        $image->proportional = false;
        $image->stretch = true;

        return $image;
    }

    public function getDefaultSize()
    {
        return [200, 150];
    }

    public function registerNode(UXNode $node)
    {
        /** @var UXImageArea $node */
        if ($node instanceof UXImageView) {
            $new = new UXImageArea();
            $new->id = $node->id;
            $new->proportional = $node->preserveRatio;

            $new->size = $node->size;
            $new->position = $node->position;
            $new->style = $node->style;
            $new->rotate = $node->rotate;
            $new->opacity = $node->opacity;

            $node->parent->children->add($new);

            $node->free();

            $node = $new;
        }

        $data = DataUtils::get($node);
        $image = $data->get('image');

        UXApplication::runLater(function () use ($image, $node) {
            if ($image) {
                $project = Ide::get()->getOpenedProject();

                if ($project) {
                    $file = $project->getFile("src/$image");

                    if ($file->exists()) {
                        $node->image = new UXImage($file);
                    }
                }
            }

            if (!$node->image) {
                $node->image = Ide::get()->getImage('dummyImage.png')->image;
            }
        });
    }

    public function isOrigin($any)
    {
        return $any instanceof UXImageArea
            || $any instanceof UXImageView;
    }
}
