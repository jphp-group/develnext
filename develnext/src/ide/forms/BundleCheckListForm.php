<?php
namespace ide\forms;

use ide\bundle\AbstractBundle;
use ide\forms\mixins\DialogFormMixin;
use ide\forms\mixins\SavableFormMixin;
use ide\Ide;
use ide\library\IdeLibraryBundleResource;
use ide\project\behaviours\BundleProjectBehaviour;
use ide\project\Project;
use ide\ui\ListMenu;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXButton;
use php\gui\UXCheckbox;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\gui\UXWebView;
use php\lib\reflect;

/**
 * Class BundleCheckListForm
 * @package ide\forms
 *
 * @property UXTabPane $tabs
 * @property UXAnchorPane $content
 * @property UXImageView $iconImage
 * @property UXLabel $titleLabel
 * @property UXLabel $descriptionLabel
 * @property UXListView $list
 * @property UXImageView $icon
 * @property UXHBox $excludePane
 * @property UXButton $addButton
 * @property UXLabel $installedLabel
 * @property UXWebView $fullDescription
 */
class BundleCheckListForm extends AbstractIdeForm
{
    use DialogFormMixin;
    use SavableFormMixin;

    /**
     * @var IdeLibraryBundleResource
     */
    protected $displayResource;

    /**
     * @var BundleProjectBehaviour
     */
    private $behaviour;

    /**
     * @var UXCheckbox[]
     */
    protected $checkboxes = [];

    /**
     * @var array
     */
    protected $groups = [
        'all' => 'Все',
        'game' => 'Игра',
        'network' => 'Интернет, сеть',
        'database' => 'Данные',
        'system' => 'Система',
        'other' => 'Другое',
    ];

    protected $groupIcons = [
        'all' => 'icons/all16.png',
        'game' => 'icons/gameMonitor16.png',
        'network' => 'icons/web16.png',
        'database' => 'icons/database16.png',
        'system' => 'icons/system16.png',
        'other' => null,
    ];

    /**
     * @var ListMenu[]
     */
    protected $tabLists = [];

    public function __construct(BundleProjectBehaviour $behaviour)
    {
        parent::__construct();
        $this->behaviour = $behaviour;
    }

    protected function init()
    {
        parent::init();

        $this->icon->image = ico('bundle32')->image;

        $this->tabs->tabs->clear();

        foreach ($this->groups as $code => $name) {
            $tab = new UXTab();
            $tab->text = $name;
            $tab->graphic = Ide::get()->getImage($this->groupIcons[$code]);

            $tabList = new ListMenu();
            $tabList->on('action', function () use ($tabList) {
                $this->display($tabList->selectedItem);
            });
            $tabList->setDescriptionGetter(function (IdeLibraryBundleResource $resource) {
                $text = $this->groups[$resource->getGroup()];

                if ($this->behaviour->hasBundleInAnyEnvironment($resource->getBundle())) {
                    $text = "✔ $text";
                }

                return $text;
            });
            $tabList->setIconGetter(function (IdeLibraryBundleResource $resource) {
                return $this->groupIcons[$resource->getGroup()];
            });

            $tab->content = $tabList;
            $this->tabLists[$code] = $tabList;

            $this->tabs->tabs->add($tab);

            $tab->on('change', function () use ($tabList) {
                uiLater(function () use ($tabList) {
                    $tabList->selectedIndex = 0;
                    $this->display($tabList->selectedItem);
                });
            });
        }
    }


    /**
     * @event showing
     */
    public function doShowing()
    {
        $this->checkboxes = [];

        foreach ($this->behaviour->getPublicBundleResources() as $resource) {
            $this->tabLists['all']->add($resource);
            $this->tabLists[$resource->getGroup()]->add($resource);
        }

        $this->display(null);

        uiLater(function () {
            $this->tabLists['all']->selectedIndex = 0;
            $this->display($this->tabLists['all']->selectedItem);
        });

        /*foreach ($bundles as $bundle) {
            $this->checkboxes[reflect::typeOf($bundle)] = $checkbox = new UXCheckbox();

            $checkbox->selected = $this->behaviour->hasBundle(Project::ENV_ALL, reflect::typeOf($bundle));
            $this->list->items->add([$checkbox, $bundle]);
        }*/
    }

    /**
     * @event saveButton.action
     */
    /*public function doSave()
    {
        $bundles = $this->behaviour->getPublicBundles();
        $result = [];

        // @var AbstractBundle $bundle
        foreach ($this->checkboxes as $class => $checkbox) {
            if ($checkbox->selected && $bundles[$class]) {
                $result[$class] = $bundles[$class];
            }
        }

        $this->setResult($result);
        $this->hide();
    } */

    /**
     * @event cancelButton.action
     */
    public function doCancel()
    {
        $this->setResult(null);
        $this->hide();
    }

    /**
     * @event addButton.action
     */
    public function doInstall()
    {
        if ($this->displayResource) {
            if (MessageBoxForm::confirm('Вы уверены, что хотите добавить этот пакет к проекту?')) {
                $this->behaviour->addBundle(Project::ENV_ALL, $this->displayResource->getBundle());
                $this->toast('Пакет расширения подключен к проекту');
                $this->update();
            }
        }
    }

    public function update()
    {
        foreach ($this->tabLists as $list) {
            $selected = $list->selectedIndex;
            $list->update();
            $list->selectedIndex = $selected;
        }

        $this->display($this->displayResource);
    }

    /**
     * @event removeButton.action
     */
    public function doUninstall()
    {
        if ($this->displayResource) {
            if (MessageBoxForm::confirmDelete('пакет расширения ' . $this->displayResource->getName())) {
                $this->behaviour->removeBundle($this->displayResource->getBundle());
                $this->toast('Пакет расширения отключен от проекта');
                $this->update();
            }
        }
    }

    public function display(IdeLibraryBundleResource $resource = null)
    {
        $this->displayResource = $resource;

        if ($resource) {
            $this->content->show();
            $this->titleLabel->text = $resource->getName();
            $this->descriptionLabel->text = $resource->getDescription();

            $icon = Ide::get()->getImage($resource->getIcon());
            $this->iconImage->image = $icon ? $icon->image : null;

            $description = $resource->getFullDescription();

            if (!$description) $description = '<span style="color:gray">Информации о содержимом нет.</span>';

            $description = "<style>i { font-style: italic !important; } ul { padding-left: 0; margin-left: 10px; } li { line-height: 20px; color: gray; }</style><h3>Пакет содержит</h3> $description";

            $description = "<div style='font: 12px Tahoma;'>$description</div>";
            $this->fullDescription->engine->loadContent($description, 'text/html');

            if ($this->behaviour->hasBundleInAnyEnvironment($resource->getBundle())) {
                $this->addButton->hide();
                $this->addButton->managed = false;

                $this->installedLabel->show();

                $this->excludePane->show();
                $this->excludePane->managed = true;
            } else {
                $this->addButton->show();
                $this->addButton->managed = true;

                $this->installedLabel->hide();

                $this->excludePane->hide();
                $this->excludePane->managed = false;
            }
        } else {
            $this->content->hide();
        }
    }
}