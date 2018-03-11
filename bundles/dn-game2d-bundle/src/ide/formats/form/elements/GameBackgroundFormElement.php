<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use ide\Ide;
use ide\project\Project;
use ide\systems\Cache;
use php\game\UXGameBackground;
use php\game\UXGameEntity;
use php\game\UXGamePane;
use php\game\UXSprite;
use php\gui\framework\DataUtils;
use php\gui\UXApplication;
use php\gui\UXImage;
use php\gui\UXImageArea;
use php\gui\UXImageView;
use php\gui\UXListView;
use php\gui\UXNode;
use php\io\Stream;

class GameBackgroundFormElement extends AbstractFormElement
{
    public function getGroup()
    {
        return '2D Игра';
    }

    public function getName()
    {
        return 'Игровой фон';
    }

    public function getIcon()
    {
        return 'icons/background16.png';
    }

    public function getIdPattern()
    {
        return "background%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $background = new UXGameBackground();

        return $background;
    }

    public function registerNode(UXNode $node)
    {
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

    public function getDefaultSize()
    {
        return [128, 128];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXGameBackground;
    }
}
