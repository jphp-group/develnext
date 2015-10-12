<?php
namespace ide\editors\form;
use behaviour\custom\BlinkAnimationBehaviour;
use ide\behaviour\AbstractBehaviourSpec;
use ide\behaviour\IdeBehaviourManager;
use ide\editors\value\BooleanPropertyEditor;
use ide\editors\value\ElementPropertyEditor;
use ide\forms\BehaviourCreateForm;
use ide\forms\MessageBoxForm;
use ide\Ide;
use php\gui\designer\UXDesignProperties;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\layout\UXHBox;
use php\gui\layout\UXPane;
use php\gui\layout\UXVBox;
use php\gui\UXButton;
use php\gui\UXLabel;
use php\gui\UXNode;
use php\util\Flow;

/**
 * Class FormBehaviourPane
 * @package ide\editors\form
 */
class FormBehaviourPane
{
    /**
     * @var IdeBehaviourManager
     */
    protected $behaviourManager;

    /**
     * @var UXVBox
     */
    protected $lastUi;

    /**
     * @param IdeBehaviourManager $manager
     */
    public function __construct(IdeBehaviourManager $manager)
    {
        $this->behaviourManager = $manager;
    }

    public function makeUi($targetId, UXVBox $box = null)
    {
        $pane = new UXDesignProperties();

        $behaviours = $this->behaviourManager->getBehaviours($targetId);

        foreach ($behaviours as $behaviour) {
            $spec = $this->behaviourManager->getBehaviourSpec($behaviour);
            $properties = $spec->getProperties();

            $code = get_class($spec);

            $pane->addGroup($code, $spec->getName());

            $groupPane = $pane->getGroupPane($code);
            $groupPane->graphic = new UXHBox([
                Ide::get()->getImage($spec->getIcon()),
                new UXLabel($spec->getName()),
            ]);

            $this->initDeleteBehaviourButton($groupPane->graphic, $targetId, $behaviour);

            $groupPane->graphic->spacing = 4;
            $groupPane->text = null;

            $groupPane->collapsible = false;

            $this->initProperties($code, $behaviour, $pane, $properties);
        }

        if ($box) {
            $box->children->clear();
            $box->children->addAll($pane->getGroupPanes());
        } else {
            $box = new UXVBox($pane->getGroupPanes());
        }

        if ($box->children->count == 0) {
            $hint = new UXLabel('Поведений нет.');
            $hint->padding = 10;
            $hint->style = '-fx-font-style: italic';

            $box->add($hint);
        }

        $box->spacing = 1;
        $box->padding = 1;

        $controlPane = new UXVBox();
        $controlPane->padding = 3;
        $box->children->insert(0, $controlPane);

        $this->initButtonAdd($controlPane, $targetId);

        return $this->lastUi = $box;
    }

    protected function initDeleteBehaviourButton(UXHBox $title, $targetId, AbstractBehaviour $behaviour)
    {
        $box = new UXHBox();
        $box->alignment = 'TOP_RIGHT';

        $button = new UXLabel();
        $button->cursor = 'HAND';
        $button->text = null;
        $button->graphic = ico('smallDelete16');

        $button->on('click', function () use ($targetId, $behaviour) {
            $spec = $this->behaviourManager->getBehaviourSpec($behaviour);

            $msg = new MessageBoxForm('Вы уверены, что хотите удалить поведение "' . $spec->getName() . '"?', ['yes' => 'Да, удалить', 'no' => 'Нет']);

            if ($msg->showDialog()) {
                if ($msg->getResult() == 'yes') {
                    $this->behaviourManager->removeBehaviour($targetId, get_class($behaviour));
                    $this->behaviourManager->save();

                    $this->makeUi($targetId, $this->lastUi);
                }
            }
        });

        $box->add($button);

        $box->maxWidth = 10000;
        UXHBox::setHgrow($box, 'ALWAYS');

        $title->add($box);
    }

    protected function initButtonAdd(UXPane $pane, $targetId)
    {
        $button = new UXButton('Добавить поведение');
        $button->graphic = ico('plugin16');

        $button->height = 30;
        $button->maxWidth = 10000;
        $button->style = '-fx-font-weight: bold;';

        $button->on('action', function () use ($targetId) {
            $dialog = new BehaviourCreateForm($this->behaviourManager, $this->behaviourManager->getTarget($targetId));

            $behaviourSpecs = Flow::of($this->behaviourManager->getBehaviours($targetId))->map(function (AbstractBehaviour $behaviour) {
                return $this->behaviourManager->getBehaviourSpec($behaviour);
            })->toArray();

            $dialog->setAlreadyAddedBehaviours($behaviourSpecs);

            if ($dialog->showDialog()) {
                /** @var AbstractBehaviourSpec $result */
                $result = $dialog->getResult();

                $class = $result->getType();

                $behaviour = new $class();
                $this->behaviourManager->apply($targetId, $behaviour);
                $this->behaviourManager->save();

                $this->makeUi($targetId, $this->lastUi);
            }
        });

        $pane->add($button);
    }

    private function initProperties($groupCode, AbstractBehaviour $behaviour, UXDesignProperties $pane, array $properties)
    {
        foreach ($properties as $code => $item) {
            /** @var ElementPropertyEditor $editor */
            $editor = $item['editorFactory']();

            $editor->setSetter(function (ElementPropertyEditor $editor, $value) use ($behaviour) {
                $behaviour->{$editor->code} = $value;
            });

            $editor->setGetter(function (ElementPropertyEditor $editor) use ($behaviour) {
                return $behaviour->{$editor->code};
            });

            $editor->setTooltip($item['tooltip']);

            $pane->addProperty($groupCode, $code, $item['name'], $editor);
        }
    }
}