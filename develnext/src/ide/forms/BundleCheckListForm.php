<?php
namespace ide\forms;

use ide\bundle\AbstractBundle;
use ide\forms\mixins\DialogFormMixin;
use ide\forms\mixins\SavableFormMixin;
use ide\Ide;
use ide\IdeConfiguration;
use ide\library\IdeLibraryBundleResource;
use ide\project\behaviours\BundleProjectBehaviour;
use ide\project\Project;
use ide\ui\ListMenu;
use php\compress\ZipFile;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXButton;
use php\gui\UXCheckbox;
use php\gui\UXDialog;
use php\gui\UXFileChooser;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\gui\UXWebView;
use php\lang\Thread;
use php\lib\fs;
use php\lib\reflect;
use php\lib\str;

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
        'other' => 'icons/blocks16.png',
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
            $tabList->setNameThin(true);
            $tabList->on('click', function () use ($tabList) {
                $this->display($tabList->selectedItem);
            });

            $tabList->setDescriptionGetter(function (IdeLibraryBundleResource $resource) {
                $text = $this->groups[$resource->getGroup()];
                return $text;
            });

            $tabList->setNameGetter(function (IdeLibraryBundleResource $resource) {
                $text = $resource->getName();

                if ($this->behaviour->hasBundleInAnyEnvironment($resource->getBundle())) {
                    $text = "$text ✔";
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

        foreach ($this->tabLists as $list) {
            $list->clear();
        }

        foreach ($this->behaviour->getPublicBundleResources() as $resource) {
            $this->tabLists['all']->add($resource);
            $this->tabLists[$resource->getGroup()]->add($resource);
        }

        if ($this->getResult() instanceof IdeLibraryBundleResource) {
            $this->display($this->getResult());

            uiLater(function () {
                foreach ($this->tabLists['all']->items as $i => $res) {
                    if ($this->getResult() == $res) {
                        $this->tabLists['all']->selectedIndex = $i;
                        $this->tabLists['all']->focusedIndex = $i;
                        $this->tabLists['all']->scrollTo($i);
                    }
                }
            });

            return;
        } else {
            $this->display(null);
        }

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
     * @event addBundle.action
     */
    public function addBundle()
    {
        $dialog = new UXFileChooser();
        $dialog->extensionFilters = [['description' => 'Пакеты для DevelNext (*.dnbundle)', 'extensions' => ['*.dnbundle']]];

        if ($file = $dialog->showOpenDialog()) {
            $zip = new ZipFile($file);

            try {
                $config = new IdeConfiguration($zip->getEntryStream('.resource'), 'UTF-8');

                if (!$config->toArray()) {
                    UXDialog::showAndWait('Поврежденный или некорректный пакет расширений');
                    $zip->close();
                } else {
                    $this->showPreloader();

                    (new Thread(function () use ($zip, $file) {
                        try {
                            $code = fs::nameNoExt($file);

                            $resource = Ide::get()->getLibrary()->makeResource('bundles', $code, true);
                            $path = fs::parent($resource->getPath()) . "/" . $code;

                            foreach ($zip->getEntryNames() as $name) {
                                if ($entry = $zip->getEntry($name)) {
                                    if ($name == '.resource') {
                                        fs::makeFile($resource->getPath() . ".resource");
                                        fs::copy($zip->getEntryStream($name), $resource->getPath() . ".resource");
                                    } else {
                                        if (str::startsWith($name, "bundle/")) {
                                            $to = $path . "/" . str::sub($name, 7);

                                            if ($entry->isDirectory()) {
                                                fs::makeDir($to);
                                            } else {
                                                fs::ensureParent($to);
                                                fs::copy($zip->getEntryStream($name), $to);
                                            }
                                        }
                                    }
                                }
                            }

                            Ide::get()->getLibrary()->update();

                            uiLater(function () use ($resource) {
                                $this->hidePreloader();
                                $this->doShowing();

                                $resource = Ide::get()->getLibrary()->findResource('bundles', $resource->getPath());
                                $this->display($resource);

                                UXDialog::showAndWait('Для завершения установки пакета перезапустите DevelNext!');

                                //$this->toast('Пакет успешно добавлен в IDE');
                            });
                        } finally {
                            $zip->close();
                        }
                    }))->start();
                }
            } finally {

            }
        }
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
            //if (MessageBoxForm::confirm('Вы уверены, что хотите добавить этот пакет к проекту?', $this)) {
                $this->behaviour->addBundle(Project::ENV_ALL, $this->displayResource->getBundle());
                //$this->toast('Пакет расширения подключен к проекту');
                $this->update();
            //}
        }
    }

    public function update()
    {
        $displayResource = $this->displayResource;

        foreach ($this->tabLists as $list) {
            $selected = $list->selectedIndex;
            $list->update();
            $list->selectedIndex = $selected;
        }

        $this->display($displayResource);
    }

    /**
     * @event removeButton.action
     */
    public function doUninstall()
    {
        if ($this->displayResource) {
           // if (MessageBoxForm::confirmDelete('пакет расширения ' . $this->displayResource->getName())) {
                $this->behaviour->removeBundle($this->displayResource->getBundle());
                //$this->toast('Пакет расширения отключен от проекта');
                $this->update();
            //}
        }
    }

    /**
     * @event deleteBundle.action
     */
    public function deleteBundle()
    {
        if (MessageBoxForm::confirmDelete('пакет расширений ' . $this->displayResource->getName(), $this)) {
            Ide::get()->getLibrary()->delete($this->displayResource);
            $this->doShowing();
        }
    }

    public function display(IdeLibraryBundleResource $resource = null)
    {
        $this->displayResource = $resource;

        if ($resource) {
            $this->titleLabel->text = $resource->getName();
            $this->descriptionLabel->text = $resource->getDescription();

            $icon = Ide::get()->getImage($resource->getIcon());
            $this->iconImage->image = $icon ? $icon->image : null;

            $description = $resource->getFullDescription();

            if (!$description) $description = '<span style="color:gray">Информации о содержимом нет.</span>';

            $description = "<style>* {line-height: 19px;} h3 { margin: 0; padding: 0; padding-bottom: 5px; } i { font-style: italic !important; } ul { margin: 0; padding: 0; padding-left: 0; margin-left: 10px; } li {  color: gray; }</style><h3>Пакет содержит</h3> $description";

            $description .= "<br><h3>Свойства</h3>
                        <div>Автор: {$resource->getAuthor()}<br>
                        Версия: {$resource->getVersion()}<br>
                        Класс: <code>" . reflect::typeOf($resource->getBundle()) . "</code></div>";

            $description = "<div style='font: 12px Tahoma;'>$description</div>";

            $this->fullDescription->engine->loadContent($description, 'text/html');

            if ($installed = $this->behaviour->hasBundleInAnyEnvironment($resource->getBundle())) {
                $this->addButton->hide();
                $this->addButton->managed = false;

                $this->installedLabel->show();
                $this->installedLabel->managed = true;

                $this->excludePane->show();
                $this->excludePane->managed = true;
            } else {
                $this->addButton->show();
                $this->addButton->managed = true;

                $this->installedLabel->hide();
                $this->installedLabel->managed = false;

                $this->excludePane->hide();
                $this->excludePane->managed = false;
            }

            $this->deleteBundle->managed = ($this->deleteBundle->visible = !$resource->isEmbedded() && !$installed);

            $this->content->show();
        } else {
            $this->content->hide();
        }
    }
}