<?php
namespace behaviour\custom;

use game\Jumping;
use game\SpriteSpec;
use php\framework\Logger;
use php\game\event\UXCollisionEvent;
use php\game\UXGameEntity;
use php\game\UXSpriteView;
use php\gui\framework\AbstractForm;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\framework\event\CollisionEventAdapter;
use php\gui\UXControl;
use php\gui\UXNode;
use php\lang\IllegalStateException;
use php\lib\str;
use script\TimerScript;

/**
 * Class GameEntityBehaviour
 * @package behaviour\custom
 *
 * @packages framework
 */
class GameEntityBehaviour extends AbstractBehaviour
{
    /**
     * @var string
     */
    public $bodyType = 'DYNAMIC';

    /**
     * @var string
     */
    public $solidType = 'NONE';

    /**
     * @var string
     */
    public $fixtureType = 'INHERITED';

    /**
     * @var bool
     */
    public $solid = false;

    /**
     * @var array
     */
    public $startVelocity = [0, 0];

    /**
     * @var array
     */
    //public $physSize = [1, 1];

    /**
     * @var UXGameEntity
     */
    protected $entity;

    /**
     * @var GameSceneBehaviour
     */
    private $foundScene;

    /**
     * @var
     */
    private $factoryName;

    /**
     * @var
     */
    private $type;

    /**
     * @param mixed $target
     * @throws IllegalStateException
     */
    protected function applyImpl($target)
    {
        /** @var UXNode $target */

        $this->factoryName = $target->data('-factory-name');
        $type = $this->type = $target->data('-factory-id') ?: ($this->factoryName ? "{$this->factoryName}.{$target->id}" : $target->id);

        if ($target instanceof UXSpriteView) {
            $target->observer('sprite')->addListener(function () {
                $this->__loadFixture();
            });
        }

        $this->entity = new UXGameEntity($type, $target);
        $this->entity->bodyType = $this->bodyType;
        $this->entity->solidType = $this->solidType;
        $this->entity->velocity = $this->startVelocity;

        if ($this->entity->solid) {
            $target->data(Jumping::DATA_SOLID_PROPERTY, true);
        }

        $fixtureLoaded = true;

        if ($this->findTargetSize()) {
            $this->__loadFixture();
        } else {
            $fixtureLoaded = false;
        }

        $collisionHandlers = $target->data(CollisionEventAdapter::class);

        if ($collisionHandlers) {
            foreach ($collisionHandlers as $entityType => $handler) {
                $this->setCollisionHandler($entityType, $handler, null);
            }

            $target->data(CollisionEventAdapter::class, null);
        }

        $action = function () use ($target, $type, $fixtureLoaded) {
            if (!$fixtureLoaded) {
                $this->__loadFixture();
            }

            $sceneBehaviour = $this->findScene();

            if ($sceneBehaviour) {
                $sceneBehaviour->getScene()->add($this->entity);
            } else {
                Logger::error("Cannot init clone '$type', scene is not found");
            }
        };

        TimerScript::executeWhile(function () use ($target, $type, &$try, $fixtureLoaded) {
            return ($fixtureLoaded || $this->findTargetSize()) && $this->findScene();
        }, $action);
    }

    protected function findTargetSize()
    {
        /** @var UXNode $target */
        $target = $this->_target;

        list($width, $height) = $target instanceof UXControl ? $target->prefSize : $target->size;

        if ($width <= 0 || $height <= 0) {
            return null;
        }

        return [$width, $height];
    }

    protected function findScene()
    {
        if ($this->foundScene) {
            return $this->foundScene;
        }

        if (!$this->_target || !$this->_target->parent) {
            return null;
        }

        $parent = $this->_target->parent;

        $sceneBehaviour = $parent->data('--game-scene');

        if (!$sceneBehaviour) {
            $sceneBehaviour = GameSceneBehaviour::get($parent);

            if (!$sceneBehaviour && $this->_target->window) {
                $sceneBehaviour = GameSceneBehaviour::get($this->_target->window);

                if (!$sceneBehaviour) {
                    $sceneBehaviour = $this->_target->window->layout->data('--game-scene');
                }
            }
        }

        return $this->foundScene = $sceneBehaviour;
    }

    public function free()
    {
        parent::free();

        if ($this->entity && $this->foundScene) {
            $this->foundScene->getScene()->remove($this->entity);
        }
    }

    public function __get($name)
    {
        if ($this->entity) {
            return $this->entity->{$name};
        }

        return null;
    }

    public function __set($name, $value)
    {
        if ($this->entity) {
            $this->entity->{$name} = $value;
        } else {
            Logger::error("Cannot set '{$name}' of '{$this->type}'");
        }
    }

    public function __call($name, array $args) {
        if ($this->entity) {
            return call_user_func([$this->entity, $name], $args);
        } else {
            Logger::error("Cannot call '{$name}()' of '{$this->type}'");
        }
    }

    public function getCode()
    {
        return 'phys';
    }

    /**
     * @return UXGameEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    public function __loadFixture()
    {
        /** @var UXNode $target */
        $target = $this->_target;

        list($width, $height) = $this->findTargetSize();

        switch ($this->fixtureType) {
            case 'ELLIPSE':
                $this->entity->setEllipseFixture($width, $height);
                break;

            case 'INHERITED':
            case 'RECTANGLE':
                $this->entity->setRectangleFixture($width, $height);
                break;

            /*case 'INHERITED':*/
                /** @var SpriteSpec $spriteSpec */
                /*if ($spriteSpec = $target->data(SpriteSpec::DATA_PROPERTY_NAME)) {
                    $fixtureData = $spriteSpec->fixtureData;

                    switch ($spriteSpec->fixtureType) {
                        case 'ELLIPSE':
                            $this->entity->setEllipseFixture($fixtureData[0], $fixtureData[1]);
                            break;
                        case 'RECTANGLE':
                            $this->entity->setRectangleFixture($fixtureData[0], $fixtureData[1]);
                            break;
                        case 'POLYGON':
                            $this->entity->setPolygonFixture($fixtureData);
                            break;
                    }
                }

                break;*/
        }

       // $this->entity->setRectangleFixture(32, 32);
    }

    public function setCollisionHandler($entityType, callable $handler, $factoryName)
    {
        $factoryName = $factoryName ?: $this->factoryName;

        if ($factoryName && !str::contains($entityType, '.')) {
            $entityType = "{$factoryName}.$entityType";
        }

        $this->entity->setCollisionHandler($entityType, function (UXCollisionEvent $e) use ($handler) {
            $event = new UXCollisionEvent($e, $e->sender->node, $e->target->node);
            $handler($event);

            if ($event->isConsumed()) {
                $e->consume();
            }
        });
    }
}