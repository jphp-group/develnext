<?php
namespace ide\forms;

use ide\behaviour\AbstractBehaviourSpec;
use ide\behaviour\IdeBehaviourManager;
use ide\forms\mixins\DialogFormMixin;
use ide\forms\mixins\SavableFormMixin;
use ide\Ide;
use php\gui\framework\AbstractForm;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXApplication;
use php\gui\UXDialog;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\lib\Items;
use php\util\SharedValue;

/**
 * Class BehaviourCreateForm
 * @package ide\forms
 *
 * @property UXImageView $icon
 * @property UXTabPane $categoryTabPane
 * @property UXListView $list
 */
class BehaviourCreateForm extends AbstractIdeForm
{
    use DialogFormMixin;
    use SavableFormMixin;

    /**
     * @var null
     */
    protected static $selectedTabName = null;

    /**
     * @var IdeBehaviourManager
     */
    protected $behaviourManager;

    /**
     * @var AbstractBehaviourSpec[]
     */
    protected $alreadyAddedBehaviours = [];

    /**
     * @var mixed
     */
    protected $target;

    /**
     * BehaviourCreateForm constructor.
     * @param IdeBehaviourManager $behaviourManager
     * @param null $target
     */
    public function __construct(IdeBehaviourManager $behaviourManager, $target = null)
    {
        parent::__construct();

        $this->behaviourManager = $behaviourManager;
        $this->target = $target;
    }

    /**
     * @param AbstractBehaviourSpec[] $alreadyAddedBehaviours
     */
    public function setAlreadyAddedBehaviours(array $alreadyAddedBehaviours)
    {
        $this->alreadyAddedBehaviours = [];

        foreach ($alreadyAddedBehaviours as $spec) {
            $this->alreadyAddedBehaviours[get_class($spec)] = $spec;
        }
    }

    protected function init()
    {
        parent::init();

        $this->icon->image = ico('plugin32')->image;

        $this->list->setCellFactory(function (UXListCell $cell, AbstractBehaviourSpec $spec) {
            $cell->text = null;

            $titleName = new UXLabel($spec->getName());
            $titleName->style = '-fx-font-weight: bold;';
            $titleName->padding = 0;

            if ($this->alreadyAddedBehaviours[get_class($spec)]) {
                $titleName->style .= '-fx-text-fill: gray';
                $titleName->text .= ' (уже добавлено)';
            }

            $titleDescription = new UXLabel($spec->getDescription());

            $box = new UXHBox([$titleName]);
            $box->spacing = 0;

            $title = new UXVBox([$box, $titleDescription]);
            $title->spacing = 0;

            $list = [];

            if ($spec->getIcon()) {
                $list[] = Ide::get()->getImage($spec->getIcon());
            }

            $list[] = $title;

            $line = new UXHBox($list);

            if ($dependencies = $spec->getDependencies()) {
                $depBox = new UXHBox();
                $depBox->spacing = 4;
                $label = new UXLabel('Зависимости: ');
                $label->style = '-fx-text-fill: gray;';
                $depBox->add($label);

                foreach ($dependencies as $i => $dep) {
                    $label = new UXLabel($dep->getName());
                    $label->tooltipText = $dep->getDescription();
                    $label->style = '-fx-text-fill: gray; -fx-font-weight: bold;';

                    $depBox->add($label);

                    if ($i != sizeof($dependencies) - 1) {
                        $depBox->add(new UXLabel(', '));
                    }
                }

                $title->add($depBox);
            }

            $line->spacing = 7;
            $line->padding = 5;
            $line->alignment = 'CENTER_LEFT';

            $cell->text = null;
            $cell->graphic = $line;
        });
    }

    /**
     * @event hide
     */
    public function doHide()
    {
        if ($this->categoryTabPane->selectedTab) {
            self::$selectedTabName = $this->categoryTabPane->selectedTab->text;
        } else {
            self::$selectedTabName = null;
        }
    }

    /**
     * @event showing
     */
    public function doShow()
    {
        $specs = $this->behaviourManager->getAllBehaviourSpecs();

        $this->categoryTabPane->tabs->clear();

        $groupedSpecs = [];

        foreach ($specs as $spec) {
            $group = $spec->getGroup();

            if ($this->target && !$spec->isAllowedFor($this->target)) {
                continue;
            }

            $groupedSpecs[$group][] = $spec;
        }

        $selectedTab = null;

        foreach ($groupedSpecs as $name => $list) {
            $tab = new UXTab();
            $tab->text = $name;
            $tab->userData = new SharedValue($list);

            $this->categoryTabPane->tabs->add($tab);

            if ($name === self::$selectedTabName) {
                $selectedTab = $tab;
            }

            $tab->on('change', function () {
                UXApplication::runLater(function () {
                    $this->updateList();
                });
            });
        }

        if ($selectedTab) {
            $this->categoryTabPane->selectedTab = $selectedTab;
        }

        UXApplication::runLater(function () {
            $this->updateList();
        });
    }

    public function updateList()
    {
        $tab = $this->categoryTabPane->selectedTab;

        $this->list->items->clear();

        if ($tab && $tab->userData instanceof SharedValue) {
            $this->list->items->addAll($tab->userData->get());
        }

        $this->list->selectedIndex = 0;
        $this->list->focusedIndex = 0;
    }

    /**
     * @event list.click-2x
     * @event addButton.action
     */
    public function doAdd()
    {
        $selected = Items::first($this->list->selectedItems);

        if ($selected) {
            if ($this->alreadyAddedBehaviours[get_class($selected)]) {
                UXDialog::show('Данное поведение уже добавлено объекту');
                return;
            }

            $this->setResult($selected);
            $this->hide();
        } else {
            UXDialog::show('Выберите поведение, чтобы его добавить');
        }
    }

    /**
     * @event cancelButton.action
     */
    public function doCancel()
    {
        $this->setResult(null);
        $this->hide();
    }
}