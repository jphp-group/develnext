<?php
namespace ide\behaviour;

use ide\Ide;
use ide\IdeException;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\lib\str;

/**
 * Class IdeBehaviourDatabase
 * @package ide\behaviour
 */
class IdeBehaviourDatabase
{
    /**
     * @var AbstractBehaviourSpec[]
     */
    protected $specs = [];

    /**
     * @var AbstractBehaviourSpec[]
     */
    protected $specsByCode = [];

    /**
     * IdeBehaviourDatabase constructor.
     */
    public function __construct()
    {
        //$this->registerInternalList('.dn/behaviours');
    }

    /**
     * @return IdeBehaviourDatabase
     */
    public static function get()
    {
        static $instance;

        if (!$instance) {
            return $instance = new IdeBehaviourDatabase();
        }

        return $instance;
    }



    /**
     * @param string $source resource path
     * @throws IdeException
     */
    public function registerInternalList($source)
    {
        $behaviours = Ide::get()->getInternalList($source);

        foreach ($behaviours as $behaviour) {
            $this->registerBehaviourSpec($behaviour);
        }
    }

    /**
     * @param string $source
     * @throws IdeException
     */
    public function unregisterInternalList($source)
    {
        $behaviours = Ide::get()->getInternalList($source);

        foreach ($behaviours as $behaviour) {
            $this->unregisterBehaviourSpec($behaviour);
        }
    }

    /**
     * @param string|AbstractBehaviourSpec $spec
     * @throws IdeException
     */
    public function registerBehaviourSpec($spec)
    {
        if (is_string($spec)) {
            $this->registerBehaviourSpec(new $spec);
        } else if ($spec instanceof AbstractBehaviourSpec) {
            $type = $spec->getType();

            $this->specs[$type] = $spec;

            /** @var AbstractBehaviour $tmp */
            $tmp = new $type();

            if ($code = $tmp->getCode()) {
                $this->specsByCode[$code] = $spec;
            }
        } else {
            throw new IdeException("Cannot register behaviour spec - $spec");
        }
    }

    /**
     * @param string|AbstractBehaviourSpec $spec
     * @throws IdeException
     */
    public function unregisterBehaviourSpec($spec)
    {
        if (is_string($spec)) {
            $this->unregisterBehaviourSpec(new $spec);
        } elseif ($spec instanceof AbstractBehaviourSpec) {
            $type = $spec->getType();

            if ($this->specs[$type]) {
                unset($this->specs[$type]);

                /** @var AbstractBehaviour $tmp */
                $tmp = new $type();

                if ($code = $tmp->getCode()) {
                    unset($this->specsByCode[$code]);
                }
            }
        } else {
            throw new IdeException("Cannot unregister behaviour spec - $spec");
        }
    }

    /**
     * @param AbstractBehaviour $behaviour
     * @return AbstractBehaviourSpec
     */
    public function getBehaviourSpec(AbstractBehaviour $behaviour)
    {
        return $this->specs[get_class($behaviour)];
    }

    public function getBehaviourSpecByClass($className)
    {
        if ($spec = $this->specsByCode[$className]) {
            return $spec;
        }

        if ($className[0] == '\\') {
            $className = str::sub($className, 1);
        }

        return $this->specs[$className];
    }

    /**
     * @return AbstractBehaviourSpec[]
     */
    public function getAllBehaviourSpecs()
    {
        return $this->specs;
    }
}