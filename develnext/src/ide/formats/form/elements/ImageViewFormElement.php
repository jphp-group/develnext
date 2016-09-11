<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use ide\Ide;
use ide\Logger;
use ide\project\Project;
use ide\systems\Cache;
use ide\utils\FileUtils;
use php\gui\framework\DataUtils;
use php\gui\UXApplication;
use php\gui\UXImage;
use php\gui\UXImageArea;
use php\gui\UXImageView;
use php\gui\UXListView;
use php\gui\UXNode;
use php\io\File;
use php\io\Stream;
use php\lang\System;
use php\time\Time;

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

    public function getElementClass()
    {
        return UXImageArea::class;
    }

    public function getIcon()
    {
        return 'icons/image16.png';
    }

    public function getIdPattern()
    {
        return "image%s";
    }

    public function getIndexData(UXNode $node)
    {
        $data = DataUtils::get($node);

        return [
            'image' => $data->get('image'),
        ];
    }

    public function getCustomPreviewImage(array $indexData)
    {
        if ($indexData['image']) {
            if (Ide::project()) {
                $file = Ide::project()->getFile("src/{$indexData['image']}");

                if ($file->isFile()) {
                    try {
                        return Cache::getImage($file);
                    } catch (\Exception $e) {
                        return null;
                    }
                }
            }
        }

        return null;
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
            $new->anchors = $node->anchors;

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
                        $node->image = Cache::getImage($file);
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
