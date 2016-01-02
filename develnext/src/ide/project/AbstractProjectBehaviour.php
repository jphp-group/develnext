<?php
namespace ide\project;
use ide\Ide;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\xml\DomElement;
use php\xml\DomDocument;

/**
 * Class AbstractProjectBehaviour
 * @package ide\project
 */
abstract class AbstractProjectBehaviour
{
    /**
     * @var Project
     */
    protected $project;

    /**
     * ...
     */
    abstract public function inject();

    /**
     * @param DomElement $domBehavior
     * @param DomDocument $document
     */
    public function serialize(DomElement $domBehavior, DomDocument $document)
    {
        // ...
    }

    /**
     * @param DomElement $domBehavior
     */
    public function unserialize(DomElement $domBehavior)
    {
        /// ...
    }

    /**
     * @param Project $project
     *
     * @return AbstractProjectBehaviour
     */
    public function forProject(Project $project)
    {
        $behavior = clone $this;
        $behavior->project = $project;

        $behavior->inject();

        return $behavior;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    static function get()
    {
        $class = get_called_class();

        if (Ide::project() && Ide::project()->hasBehaviour($class)) {
            return Ide::project()->getBehaviour($class);
        }

        return null;
    }
}