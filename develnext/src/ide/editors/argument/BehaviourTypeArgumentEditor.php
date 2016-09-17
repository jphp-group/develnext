<?php
namespace ide\editors\argument;


use ide\behaviour\AbstractBehaviourSpec;
use ide\behaviour\IdeBehaviourDatabase;
use ide\behaviour\IdeBehaviourManager;
use ide\editors\common\ObjectListEditorItem;
use ide\Ide;
use php\gui\framework\behaviour\custom\AbstractBehaviour;

class BehaviourTypeArgumentEditor extends EnumArgumentEditor
{
    /**
     * @var IdeBehaviourDatabase
     */
    protected $manager;

    /**
     * @param IdeBehaviourDatabase $behaviourDatabase
     */
    public function __construct(IdeBehaviourDatabase $behaviourDatabase)
    {
        $this->manager = $behaviourDatabase;

        parent::__construct([]);

        $this->updateUi();
    }

    /**
     * @return mixed
     */
    public function updateUi()
    {
        $variants = [];

        $byGroup = [];

        foreach ($this->manager->getAllBehaviourSpecs() as $spec) {
            $byGroup[$spec->getGroup()][] = $spec;
        }

        foreach ($byGroup as $name => $specs) {
            $variants[] = new ObjectListEditorItem("[$name]");

            /** @var AbstractBehaviourSpec $spec */
            foreach ($specs as $spec) {
                $type = $spec->getType();

                /** @var AbstractBehaviour $tmp */
                $tmp = new $type();

                $variants[] = new ObjectListEditorItem(
                    $spec->getName() . ($tmp->getCode() ? " [{$tmp->getCode()}]" : ""),
                    Ide::get()->getImage($spec->getIcon()),
                    /*$tmp->getCode() ?:*/ $type,
                    1
                );
            }
        }

        $this->options = $variants;

        parent::updateUi();
    }
}