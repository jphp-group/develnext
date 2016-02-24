<?php
namespace ide\behaviour;

use ide\Ide;
use ide\utils\FileUtils;
use php\format\ProcessorException;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\framework\behaviour\custom\BehaviourLoader;
use php\gui\framework\behaviour\custom\BehaviourManager;
use php\lang\NotImplementedException;
use php\lib\Str;
use php\xml\DomDocument;
use php\xml\XmlProcessor;

/**
 * Class IdeBehaviourManager
 * @package ide\behaviour
 */
class IdeBehaviourManager extends BehaviourManager
{
    /**
     * @var AbstractBehaviourSpec[]
     */
    protected static $specs = [];

    /**
     * @var AbstractBehaviourSpec[]
     */
    protected static $specsByCode = [];

    /**
     * @var string
     */
    protected $file;

    /**
     * @var AbstractBehaviour[]
     */
    protected $behaviours;

    /**
     * @var callable
     */
    protected $targetGetter = null;

    /**
     * @var array
     */
    protected $undeletable = [];

    /**
     * @param $file
     * @param callable $targetGetter
     */
    public function __construct($file, callable $targetGetter = null)
    {
        if (!self::$specs) {
            $behaviours = Ide::get()->getInternalList('.dn/behaviours');

            foreach ($behaviours as $behaviour) {
                /** @var AbstractBehaviourSpec $behaviour */
                $behaviour = new $behaviour();

                $type = $behaviour->getType();

                self::$specs[$type] = $behaviour;

                /** @var AbstractBehaviour $tmp */
                $tmp = new $type();

                if ($code = $tmp->getCode()) {
                    self::$specsByCode[$code] = $behaviour;
                }
            }
        }

        $this->file = $file;
        $this->targetGetter = $targetGetter;
    }

    /**
     * @param AbstractBehaviour $behaviour
     * @return AbstractBehaviourSpec
     */
    public function getBehaviourSpec(AbstractBehaviour $behaviour)
    {
        return self::$specs[get_class($behaviour)];
    }

    public function getBehaviourSpecByClass($className)
    {
        if ($spec = self::$specsByCode[$className]) {
            return $spec;
        }

        if ($className[0] == '\\') {
            $className = Str::sub($className, 1);
        }

        return self::$specs[$className];
    }

    /**
     * @return AbstractBehaviourSpec[]
     */
    public function getAllBehaviourSpecs()
    {
        return self::$specs;
    }

    /**
     * @param $targetId
     * @return AbstractBehaviour[]
     */
    public function getBehaviours($targetId)
    {
        return (array) $this->behaviours["$targetId"];
    }

    /**
     * @param $targetId
     */
    public function clearBehaviours($targetId)
    {
        unset($this->behaviours["$targetId"]);
    }

    /**
     * @param $targetId
     * @param $type
     */
    public function removeBehaviour($targetId, $type)
    {
        unset($this->behaviours["$targetId"][$type]);
    }

    /**
     * @param $targetId
     * @param $type
     * @return bool
     */
    public function hasBehaviour($targetId, $type)
    {
        return !!$this->behaviours["$targetId"][$type];
    }

    /**
     * @param $targetId
     */
    public function removeBehaviours($targetId)
    {
        unset($this->behaviours["$targetId"]);
    }

    public function duplicateBehaviours($targetId, $copyTargetId)
    {
        $behaviours = $this->getBehaviours($targetId);

        foreach ($behaviours as $behaviour) {
            $type = get_class($behaviour);

            /** @var AbstractBehaviour $copy */
            $copy = new $type();
            $copy->setProperties($behaviour->getProperties());

            $this->apply($copyTargetId, $copy);
        }

        if ($behaviours) {
            $this->save();
        }
    }

    /**
     * @param $oldId
     * @param $newId
     */
    public function changeTargetId($oldId, $newId)
    {
        if ($this->behaviours["$oldId"]) {
            $this->behaviours["$newId"] = $this->behaviours["$oldId"];
            unset($this->behaviours["$oldId"]);

            $this->save();
        }
    }

    public function load()
    {
        if (!$this->file) return;

        $this->behaviours = [];

        BehaviourLoader::load($this->file, $this);
    }

    public function dump(DomDocument $document, array $targetIds = [])
    {
        $root = $document->createElement('behaviours');

        foreach ($targetIds as $targetId) {
            $behaviours = (array) $this->behaviours["$targetId"];

            if ($behaviours) {
                $target = $document->createElement('target', ['@id' => "$targetId"]);
                $root->appendChild($target);

                /**
                 * @var string $type
                 * @var AbstractBehaviour $behaviour
                 */
                foreach ($behaviours as $type => $behaviour) {
                    $item = $document->createElement('behaviour', ['@type' => $type]);

                    $attributes = $behaviour->getProperties();

                    if ($attributes['enabled']) {
                        unset($attributes['enabled']);
                    }

                    foreach ($attributes as $name => &$value) {
                        if (is_array($value)) {
                            $value = "[" . str::join($value, ',') . "]";
                        }
                    }

                    $item->setAttributes($attributes);

                    $target->appendChild($item);
                }
            }
        }

        return $root;
    }

    public function save()
    {
        if (!$this->file) return;

        $xml = new XmlProcessor();

        $document = $xml->createDocument();

        $root = $document->createElement('behaviours');
        $document->appendChild($root);

        foreach ($this->behaviours as $targetId => $behaviours) {
            if (!$this->getTarget($targetId)) {
                continue;
            }

            $target = $document->createElement('target', ['@id' => $targetId]);

            $root->appendChild($target);

            /**
             * @var string $type
             * @var AbstractBehaviour $behaviour
             */
            foreach ($behaviours as $type => $behaviour)  {
                $item = $document->createElement('behaviour', ['@type' => $type]);

                $attributes = $behaviour->getProperties();

                if ($attributes['enabled']) {
                    unset($attributes['enabled']);
                }

                foreach ($attributes as &$value) {
                    if (is_array($value)) {
                        $value = "[" . str::join($value, ',') . "]";
                    }
                }

                $item->setAttributes($attributes);

                $target->appendChild($item);
            }
        }

        FileUtils::put($this->file, $xml->format($document));
    }

    /**
     * @param $targetId
     * @param AbstractBehaviour $behaviour
     * @return mixed
     */
    public function apply($targetId, AbstractBehaviour $behaviour)
    {
        if ($this->behaviours["$targetId"][get_class($behaviour)]) {
            unset($this->behaviours["$targetId"][get_class($behaviour)]);
        }

        $this->behaviours["$targetId"][get_class($behaviour)] = $behaviour;
    }

    /**
     * @param callable $targetGetter
     */
    public function setTargetGetter(callable $targetGetter)
    {
        $this->targetGetter = $targetGetter;
    }

    /**
     * @param $targetId
     * @return mixed|null
     */
    public function getTarget($targetId)
    {
        if (!$this->targetGetter) {
            return null;
        }

        return call_user_func($this->targetGetter, $targetId);
    }

    /**
     * @param $target
     * @param $type
     * @return AbstractBehaviour
     * @throws NotImplementedException
     */
    public function getBehaviour($target, $type)
    {
        throw new NotImplementedException();
    }
}