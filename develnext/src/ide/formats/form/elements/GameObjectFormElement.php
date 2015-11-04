<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use ide\Ide;
use ide\project\Project;
use php\game\UXGameObject;
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

class GameObjectFormElement extends AbstractFormElement
{
    public function getGroup()
    {
        return '2D Игра';
    }

    public function getName()
    {
        return 'Игровой объект';
    }

    public function getIcon()
    {
        return 'icons/gameObject16.png';
    }

    public function getIdPattern()
    {
        return "gameObject%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $sprite = new UXSprite();
        $sprite->image = ico('grayQuestion16')->image;

        $object = new UXGameObject($sprite);

        UXApplication::runLater(function () use ($object) {
            if ($object->parent && !($object->parent->userData instanceof UXGamePane)) {
                Ide::get()->getMainForm()->toast('Игровые объекты лучше добавлять на игровое поле');
            }
        });

        return $object;
    }

    public function getDefaultSize()
    {
        return [32, 32];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXGameObject;
    }
}
