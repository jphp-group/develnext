<?php
namespace ide\editors\argument;


use ide\behaviour\AbstractBehaviourSpec;
use ide\behaviour\IdeBehaviourManager;
use ide\editors\common\ObjectListEditorItem;
use ide\Ide;

class BehaviourTypeArgumentEditor extends EnumArgumentEditor
{
    /**
     * @var IdeBehaviourManager
     */
    protected $manager;

    /**
     * @param IdeBehaviourManager $behaviourManager
     */
    public function __construct(IdeBehaviourManager $behaviourManager)
    {
        $this->manager = $behaviourManager;

        $variants = [];

        $byGroup = [];

        foreach ($this->manager->getAllBehaviourSpecs() as $spec) {
            $byGroup[$spec->getGroup()][] = $spec;
        }

        foreach ($byGroup as $name => $specs) {
            $variants[] = new ObjectListEditorItem("[$name]");

            /** @var AbstractBehaviourSpec $spec */
            foreach ($specs as $spec) {
                $variants[] = new ObjectListEditorItem($spec->getName(), Ide::get()->getImage($spec->getIcon()), $spec->getType(), 1);
            }
        }

        parent::__construct($variants);
    }
}