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
use ide\Logger;
use ide\misc\EventHandlerBehaviour;
use php\gui\designer\UXDesignProperties;
use php\gui\event\UXEvent;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\layout\UXHBox;
use php\gui\layout\UXPane;
use php\gui\layout\UXVBox;
use php\gui\UXButton;
use php\gui\UXLabel;
use php\gui\UXNode;
use php\gui\UXTitledPane;
use php\util\Flow;

/**
 * Class FormBehaviourPane
 * @package ide\editors\form
 */
class IdeBehaviourPane
{
    use EventHandlerBehaviour;

    /**
     * @var IdeBehaviourManager
     */
    protected $behaviourManager;

    /**
     * @var UXVBox
     */
    protected $lastUi;

    /**
     * @var UXDesignProperties
     */
    protected $pane;

    /**
     * @var array
     */
    protected $targetBehaviours = [];

    /**
     * @var UXNode
     */
    protected $hintNode;

    /**
     * @var string
     */
    protected $hintNodeText;

    /**
     * @var mixed
     */
    protected $targetId;

    /**
     * @param IdeBehaviourManager $manager
     */
    public function __construct(IdeBehaviourManager $manager)
    {
        $this->behaviourManager = $manager;
    }

    /**
     * @return mixed
     */
    public function getHintNode()
    {
        return $this->hintNode;
    }

    /**
     * @param mixed $hintNode
     */
    public function setHintNode($hintNode)
    {
        $this->hintNode = $hintNode;
        $this->hintNodeText = $hintNode->text;
    }

    public function makeUi($targetId, UXVBox $box = null)
    {
        $this->targetId = $targetId;
        if ($this->pane) {
            $pane = $this->pane;
        } else {
            $pane = $this->pane = new UXDesignProperties();
        }

        $behaviours = $this->behaviourManager->getBehaviours($targetId);
        $groupPanes = [];

        $this->targetBehaviours = $behaviours;

        foreach ($behaviours as $class => $behaviour) {
            $spec = $this->behaviourManager->getBehaviourSpec($behaviour);
            $properties = $spec->getProperties();

            $code = $class;

            $groupPane = $pane->getGroupPane($code);

            if (!$groupPane) {
                $pane->addGroup($code, $spec->getName());

                $groupPane = $pane->getGroupPane($code);
                $groupPane->graphic = new UXHBox([
                    Ide::get()->getImage($spec->getIcon()),
                    new UXLabel($spec->getName()),
                ]);

                if ($spec->isDeletable()) {
                    $this->initDeleteBehaviourButton($groupPane, $spec);
                }

                $groupPane->graphic->spacing = 4;
                $groupPane->text = null;

                $groupPane->collapsible = true;

                $this->initProperties($code, $pane, $properties);
            }

            $groupPane->data('--target-id', $targetId);
            $groupPanes[$code] = $groupPane;
        }

        if ($box) {
            $box->children->clear();
            $box->children->addAll($groupPanes);
        } else {
            $box = new UXVBox($groupPanes);
        }

        foreach ($groupPanes as $code => $one) {
            $this->pane->updateOne($code);
        }

        if ($this->hintNode) {
            $this->hintNode->text = "{$this->hintNodeText} [{$box->children->count}]";
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

    protected function initDeleteBehaviourButton(UXTitledPane $pane, AbstractBehaviourSpec $spec)
    {
        $box = new UXHBox();

        $button = new UXLabel();
        $button->cursor = 'HAND';
        $button->text = null;
        $button->graphic = ico('smallDelete16');

        $targetId = $pane->data('--target-id');

        $button->on('click', function (UXEvent $e) use ($targetId, $spec, $pane) {
            $msg = new MessageBoxForm('Вы уверены, что хотите удалить поведение "' . $spec->getName() . '"?', ['yes' => 'Да, удалить', 'no' => 'Нет']);

            uiLater(function () use ($pane) {
                $pane->expanded = !$pane->expanded;
            });

            if ($msg->showDialog()) {
                if ($msg->getResult() == 'yes') {
                    $this->behaviourManager->removeBehaviour($targetId, $spec->getType());
                    $this->behaviourManager->save();

                    $this->makeUi($targetId, $this->lastUi);

                    $this->trigger('remove', [$spec->getType()]);
                }
            }
        });

        $box->add($button);
        $pane->graphic->add($box);
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

                $behaviour = $result->createBehaviour();
                $this->behaviourManager->apply($targetId, $behaviour);
                $this->behaviourManager->save();

                $this->makeUi($targetId, $this->lastUi);
            }
        });

        $pane->add($button);
    }

    private function initProperties($class, UXDesignProperties $pane, array $properties)
    {
        foreach ($properties as $code => $item) {
            /** @var ElementPropertyEditor $editor */
            $editor = $item['editorFactory']();

            $editor->setSetter(function (ElementPropertyEditor $editor, $value) use ($class) {
                if ($this->targetBehaviours[$class]) {
                    $this->targetBehaviours[$class]->{$editor->code} = $value;
                }
            });

            $editor->setGetter(function (ElementPropertyEditor $editor) use ($class) {
                if (!$this->targetBehaviours[$class]) {
                    return null;
                }

                return $this->targetBehaviours[$class]->{$editor->code};
            });

            $editor->setTooltip($item['tooltip']);

            $pane->addProperty($class, $code, $item['name'], $editor);
        }
    }

    /**
     * @return mixed
     */
    public function getTargetId()
    {
        return $this->targetId;
    }
}