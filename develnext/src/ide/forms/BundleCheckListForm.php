<?php
namespace ide\forms;

use ide\bundle\AbstractBundle;
use ide\forms\mixins\DialogFormMixin;
use ide\forms\mixins\SavableFormMixin;
use ide\Ide;
use ide\project\behaviours\BundleProjectBehaviour;
use ide\project\Project;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXCheckbox;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\lib\reflect;

/**
 * Class BundleCheckListForm
 * @package ide\forms
 *
 * @property UXListView $list
 * @property UXImageView $icon
 */
class BundleCheckListForm extends AbstractIdeForm
{
    use DialogFormMixin;
    use SavableFormMixin;

    /**
     * @var BundleProjectBehaviour
     */
    private $behaviour;

    /**
     * @var UXCheckbox[]
     */
    protected $checkboxes = [];

    public function __construct(BundleProjectBehaviour $behaviour)
    {
        parent::__construct();
        $this->behaviour = $behaviour;
    }

    protected function init()
    {
        parent::init();

        $this->icon->image = ico('bundleMake32')->image;

        $this->list->setCellFactory(function (UXListCell $cell, array $item) {
            /** @var AbstractBundle $bundle */
            /** @var UXCheckbox $checkbox */
            list($checkbox, $bundle) = $item;

            $icon = Ide::get()->getImage($bundle->getIcon() ?: 'icons/bundle32.png');

            $label = new UXLabel($bundle->getName());
            $label->font = $label->font->withBold();
            $label->textColor = 'black';

            $description = new UXLabel($bundle->getDescription());
            $description->textColor = 'gray';

            $title = new UXVBox([$label, $description, $checkbox]);

            $hbox = new UXHBox([$icon, $title]);
            $hbox->spacing = 10;

            $checkbox->text = 'Подключить';
            $checkbox->textColor = 'black';

            $ui = new UXVBox([$hbox]);
            $ui->alignment = 'CENTER_LEFT';
            $ui->spacing = 5;
            $ui->padding = 5;

            $cell->graphic = $ui;
            $cell->text = null;
        });
    }


    /**
     * @event showing
     */
    public function doShowing()
    {
        $this->list->items->clear();
        $this->checkboxes = [];

        $bundles = $this->behaviour->getPublicBundles();

        foreach ($bundles as $bundle) {
            $this->checkboxes[reflect::typeOf($bundle)] = $checkbox = new UXCheckbox();

            $checkbox->selected = $this->behaviour->hasBundle(Project::ENV_ALL, reflect::typeOf($bundle));
            $this->list->items->add([$checkbox, $bundle]);
        }
    }

    /**
     * @event saveButton.action
     */
    public function doSave()
    {
        $bundles = $this->behaviour->getPublicBundles();
        $result = [];

        /** @var AbstractBundle $bundle */
        foreach ($this->checkboxes as $class => $checkbox) {
            if ($checkbox->selected && $bundles[$class]) {
                $result[$class] = $bundles[$class];
            }
        }

        $this->setResult($result);
        $this->hide();
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