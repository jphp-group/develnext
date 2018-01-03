<?php

namespace ide\project\behaviours\bundle;

use ide\forms\BundleDetailInfoForm;
use ide\forms\InputMessageBoxForm;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\IdeConfiguration;
use ide\library\IdeLibraryBundleResource;
use ide\Logger;
use ide\misc\AbstractCommand;
use ide\project\behaviours\BundleProjectBehaviour;
use ide\project\control\AbstractProjectControlPane;
use ide\project\Project;
use ide\systems\Cache;
use ide\systems\FileSystem;
use ide\systems\IdeSystem;
use ide\systems\ProjectSystem;
use ide\ui\FlowListViewDecorator;
use ide\ui\ImageBox;
use ide\ui\ImageExtendedBox;
use ide\utils\FileUtils;
use ide\utils\UiUtils;
use php\compress\ZipException;
use php\compress\ZipFile;
use php\gui\event\UXMouseEvent;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\gui\UXFileChooser;
use php\gui\UXHyperlink;
use php\gui\UXLabel;
use php\gui\UXNode;
use php\gui\UXSeparator;
use php\gui\UXTextField;
use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lang\Thread;
use php\lib\fs;
use php\lib\str;

class BundlesProjectControlPane extends AbstractProjectControlPane
{
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
     * @var UXHyperlink[]
     */
    protected $groupLinks = [];

    /**
     * @var BundleProjectBehaviour
     */
    protected $behaviour;

    /**
     * @var FlowListViewDecorator
     */
    protected $availableBundleListPane;

    /**
     * @var FlowListViewDecorator
     */
    protected $projectBundleListPane;

    /**
     * BundlesProjectControlPane constructor.
     * @param BundleProjectBehaviour $behaviour
     */
    public function __construct(BundleProjectBehaviour $behaviour)
    {
        $this->behaviour = $behaviour;
    }

    public function getName()
    {
        return "Пакеты";
    }

    public function getDescription()
    {
        return "Пакеты расширений";
    }

    function getMenuCount()
    {
        $count = 0;

        foreach ($this->behaviour->getPublicBundleResources() as $resource) {
            if ($this->behaviour->hasBundleInAnyEnvironment($resource->getBundle())) {
                $count++;
            }
        }

        return $count;
    }

    public function getIcon()
    {
        return 'icons/pluginEx16.png';
    }

    /**
     * @return UXNode
     */
    protected function makeUi()
    {
        $this->availableBundleListPane = new FlowListViewDecorator();
        $this->availableBundleListPane->setMultipleSelection(false);
        $this->availableBundleListPane->clearMenuCommands();

        $this->projectBundleListPane = new FlowListViewDecorator();
        $this->projectBundleListPane->setEmptyListText('Перетащите сюда необходимые пакеты расширений ...');
        $pane = $this->projectBundleListPane->getPane();

        $pane->minHeight = 212;
        $pane->height = 212;
        $pane->maxHeight = 212;

        $this->projectBundleListPane->on('remove', function (array $nodes) {
            foreach ($nodes as $node) {
                /** @var IdeLibraryBundleResource $resource */
                $resource = $node->data('resource');
                $this->behaviour->removeBundle($resource->getBundle());
            }

            $this->refresh();

            if ($editor = FileSystem::getSelectedEditor()) {
                $editor->open();
                $editor->refresh();
            }
        });

        $this->projectBundleListPane->on('append', function ($index, $indexes) {
            $this->projectBundleListPane->clearSelections();
            $node = $this->availableBundleListPane->getSelectionNode();

            /** @var IdeLibraryBundleResource $resource */
            $resource = $node->data('resource');

            Ide::get()->getMainForm()->showPreloader('Подождите, подключение пакета ...');

            Ide::async(function () use ($resource) {
                try {
                    $this->behaviour->addBundle(Project::ENV_ALL, $resource->getBundle());
                } finally {
                    uiLater(function () {
                        Ide::get()->getMainForm()->hidePreloader();
                    });
                }

                uiLater(function () use ($resource) {
                    $this->projectBundleListPane->add($this->makeItemUi($resource));

                    $this->availableBundleListPane->removeBySelections();

                    if ($editor = FileSystem::getSelectedEditor()) {
                        $editor->open();
                        $editor->refresh();
                    }

                    Ide::toast("Пакет расширения '{$resource->getName()}' подключен к проекту");
                });
            });
        });

        $label = new UXLabel("Доступные пакеты:");
        $label->font->bold = true;

        $label2 = new UXLabel("Пакеты проекта:");
        $label2->font->bold = true;

        $vbox = new UXVBox([$label2, $pane, new UXSeparator(), $label, $this->makeActionPaneUi(), $this->availableBundleListPane->getPane()], 10);
        UXVBox::setVgrow($this->availableBundleListPane->getPane(), 'ALWAYS');
        UXVBox::setVgrow($vbox, 'ALWAYS');

        return $vbox;
    }

    private function makeActionPaneUi()
    {
        $box = new UXHBox([], 10);
        $box->alignment = 'CENTER_LEFT';
        $box->minHeight = 32;

        $searchField = new UXTextField();
        $searchField->promptText = 'поиск пакета ...';
        $searchField->width = 220;
        $searchField->maxHeight = 999;

        $box->add($searchField);

        $searchBtn = new UXButton();
        $searchBtn->graphic = ico('flatSearch16');
        $searchBtn->maxHeight = 999;

        $searchAction = function () use ($searchField) {
            $this->availableBundleListPane->clear();

            Ide::async(function () use ($searchField) {
                $this->refresh('all', $searchField->text);
            });
        };

        $searchField->on('keyUp', $searchAction);
        $searchBtn->on('action', $searchAction);

        $box->add($searchBtn);
        $box->add(new UXSeparator('VERTICAL'));

        $addToLibrary = new UXButton('Добавить пакет из файла', ico('library16'));
        $addToLibrary->maxHeight = 999;
        $addToLibrary->on('action', [$this, 'addBundleFile']);
        $box->add($addToLibrary);

        $addUrlToLibrary = new UXButton('Добавить пакет по URL', ico('linkAdd16'));
        $addUrlToLibrary->maxHeight = 999;
        $addUrlToLibrary->on('action', [$this, 'addBundleUrl']);
        $box->add($addUrlToLibrary);

        return $box;
    }

    private function makeItemUi(IdeLibraryBundleResource $resource)
    {
        $item = new ImageBox(92, 52);
        $item->setImage(Ide::getImage($resource->getIcon())->image);
        $item->setTitle($resource->getName() . ' ' . $resource->getVersion());
        $item->setTooltip($resource->getDescription());
        $item->data('resource', $resource);

        $item->on('click', function (UXMouseEvent $e) use ($resource) {
            if ($e->clickCount >= 2) {
                $this->showBundleDialog($resource);
            }
        });

        return $item;
    }

    private function makeExtendedItemUi(IdeLibraryBundleResource $resource)
    {
        $item = new ImageExtendedBox(42, 42);
        $item->style = '-fx-border-width: 1px; -fx-border-color: silver;';
        $item->maxWidth = $item->minWidth = 350;
        $item->setImage(Ide::getImage($resource->getIcon())->image);
        $item->setTitle($resource->getName() . ' (' . $resource->getVersion() . ')');
        $item->setDescription($resource->getDescription(), '-fx-text-fill: gray');
        $item->setTooltip($resource->getDescription());
        $item->data('resource', $resource);

        $item->on('click', function (UXMouseEvent $e) use ($resource) {
            if ($e->clickCount >= 2) {
                $this->showBundleDialog($resource);
            }
        });

        return $item;
    }

    public function showBundleDialog(IdeLibraryBundleResource $resource)
    {
        $dialog = new BundleDetailInfoForm($this->behaviour);
        $dialog->onUpdate(function () {
            $this->refresh();
        });

        $dialog->setResult($resource);
        $dialog->showDialog();

        $this->refresh();
    }

    public function addBundle($file, callable $callback = null)
    {
        if (!$callback) $callback = function ($result) {};

        try {
            uiLater(function () {
                Ide::get()->getMainForm()->showPreloader('Подождите, установка пакета ...');
            });

            $zip = new ZipFile($file);

            try {
                if (!$zip->has('.resource')) {
                    uiLater(function () {
                        MessageBoxForm::warning('Поврежденный или некорректный пакет расширений, т.к. файл .resource отсутствует.');
                    });

                    $callback(false);

                    return false;
                }

                /** @var IdeConfiguration $config */
                $config = null;
                $exit = false;

                $zip->read('.resource', function ($stat, Stream $stream) use (&$exit, &$config) {
                    $config = new IdeConfiguration($stream, $stat['name']);

                    if (!$config->toArray()) {
                        uiLater(function () {
                            MessageBoxForm::warning('Поврежденный или некорректный пакет расширений, в конфиге .resource нет данных.');
                        });

                        $exit = true;
                        return;
                    }
                });

                if ($exit) {
                    $callback(false);
                    return false;
                }

                uiLater(function () use ($config, $zip, $file, $callback) {
                    $version = $config->get('version', '1.0');
                    $code = $config->get("name") . '~' . $version;

                    if ($oldResource = Ide::get()->getLibrary()->getResource('bundles', $config->get("name"))) {
                        if (!$oldResource->isEmbedded()) {
                            if (MessageBoxForm::confirm("Данный пакет уже установлен, заменить его новой версией ({$oldResource->getVersion()} -> {$version})?")) {
                                IdeSystem::getLoader()->invalidateByteCodeCache();
                                Ide::get()->getLibrary()->delete($oldResource);
                            } else {
                                Ide::get()->getMainForm()->hidePreloader();
                                $callback(false);
                                return;
                            }
                        }
                    }

                    (new Thread(function () use ($zip, $file, $config, $code, $callback) {
                        try {
                            $resource = Ide::get()->getLibrary()->makeResource('bundles', $code, true);
                            $path = fs::parent($resource->getPath()) . "/" . $code;

                            foreach ($zip->statAll() as $stat) {
                                $name = $stat['name'];

                                if ($name == '.resource') {
                                    fs::makeFile($resource->getPath() . ".resource");

                                    $zip->read($name, function ($_, Stream $stream) use ($resource) {
                                        fs::copy($stream, $resource->getPath() . ".resource");
                                    });
                                } else {
                                    if (str::startsWith($name, "bundle/")) {
                                        $to = $path . "/" . str::sub($name, 7);

                                        if ($stat['directory']) {
                                            fs::makeDir($to);
                                        } else {
                                            fs::ensureParent($to);
                                            try {
                                                $zip->read($name, function ($_, Stream $stream) use ($to) {
                                                    fs::copy($stream, $to);
                                                });
                                            } catch (IOException $e) {
                                                // jphp bug.
                                            }
                                        }
                                    }
                                }
                            }

                            Ide::get()->getLibrary()->updateCategory('bundles');

                            uiLater(function () use ($resource) {
                                Ide::get()->getMainForm()->hidePreloader();
                                $this->refresh();

                                $msg = new MessageBoxForm('Для корректного завершения установки пакета перезапустите DevelNext!', ['Перезапустить', 'Позже']);

                                if ($msg->showWarningDialog() && $msg->getResultIndex() == 0) {
                                    Ide::get()->restart();
                                }

                                /** @var IdeLibraryBundleResource $resource */
                                $resource = Ide::get()->getLibrary()->getResource('bundles', $resource->getUniqueId());

                                if ($resource && $resource->getBundle()) {
                                    if ($env = $this->behaviour->hasBundleInAnyEnvironment($resource->getBundle())) {
                                        $this->behaviour->removeBundle($resource->getBundle());
                                        $this->behaviour->addBundle($env, $resource->getBundle());
                                    }
                                }
                            });

                            $callback(false);
                        } finally {
                            ;
                        }
                    }))->start();
                });

                return true;
            } finally {

            }
        } catch (ZipException $e) {
            uiLater(function () {
                MessageBoxForm::warning('Поврежденный или некорректный файл ZIP пакета расширений');
                Ide::get()->getMainForm()->hidePreloader();
            });

            return false;
        }
    }

    public function addBundleFile()
    {
        $dialog = new UXFileChooser();
        $dialog->extensionFilters = [['description' => 'Пакеты для DevelNext (*.dnbundle)', 'extensions' => ['*.dnbundle']]];

        if ($file = $dialog->showOpenDialog()) {
            $this->addBundle($file);
        }
    }

    public function addBundleUrl()
    {
        $dialog = new InputMessageBoxForm('Добавление пакета по URL', 'Ссылка на пакет расширения (URL):', '* Введите ссылку на *.dnbundle файл');

        if ($dialog->showDialog() && $dialog->getResult()) {
            $file = Ide::get()->createTempFile('.dnbundle');

            Ide::get()->getMainForm()->showPreloader('Подождите, загрузка пакета ...');

            Ide::async(function () use ($dialog, $file) {
                FileUtils::copyFile($dialog->getResult(), $file);

                if (!fs::exists($file)) {
                    uiLater(function () {
                        UXDialog::show('Ошибка загрузки пакета');
                    });
                    return;
                }

                uiLater(function () {
                    Ide::get()->getMainForm()->showPreloader('Подождите, установка пакета ...');
                });

                $this->addBundle($file, function () use ($file) {
                    if (!$file->delete()) {
                        $file->deleteOnExit();
                    }

                    uiLater(function () {
                        Ide::get()->getMainForm()->hidePreloader();
                    });
                });
            });
        }
    }

    /**
     * Refresh ui and pane.
     * @param string $groupCode
     * @param null $searchText
     */
    public function refresh($groupCode = 'all', $searchText = null)
    {
        uiLater(function () use ($groupCode) {
            foreach ($this->groupLinks as $code => $link) {
                $link->underline = $code == $groupCode;
            }

            $this->availableBundleListPane->clear();
            $this->projectBundleListPane->clear();
        });

        foreach ($this->behaviour->getPublicBundleResources() as $resource) {
            if (!$resource->isSupport(Ide::project())) {
                continue;
            }

            if ($resource->getGroup() == $groupCode || $groupCode == 'all') {
                // skip if bundle already in project.
                $bundle = $resource->getBundle();

                if ($this->behaviour->hasBundleInAnyEnvironment($bundle)) {
                    uiLater(function () use ($resource) {
                        $this->projectBundleListPane->add($this->makeItemUi($resource));
                    });
                } else {
                    if ($searchText) {
                        $string = $resource->getName() . ' ' . $resource->getDescription() . ' ' . $resource->getGroup() . ' ' . $this->groups[$resource->getGroup()];
                        $string = str::lower($string);

                        if (!str::contains($string, str::lower($searchText))) {
                            continue;
                        }
                    }

                    uiLater(function () use ($resource) {
                        $this->availableBundleListPane->add($this->makeExtendedItemUi($resource));
                    });
                }
            }
        }
    }
}