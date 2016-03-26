<?php
namespace ide\editors;

use ide\Ide;
use ide\Logger;
use ide\utils\FileUtils;
use php\gui\framework\AbstractForm;
use php\gui\framework\EventBinder;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXVBox;
use php\gui\UXApplication;
use php\gui\UXDesktop;
use php\gui\UXDialog;
use php\gui\UXLabel;
use php\gui\UXLoader;
use php\gui\UXNode;
use php\gui\UXSeparator;
use php\io\File;
use php\io\ResourceStream;
use php\io\Stream;
use php\lib\str;

class ProjectEditor extends AbstractEditor
{
    /**
     * @var UXAnchorPane
     */
    protected $ui;

    /**
     * @var UXVBox
     */
    protected $content;

    /**
     * @var UXLabel
     */
    protected $projectNameLabel;

    /**
     * @var bool
     */
    protected $init = false;

    /**
     * @var UXLabel
     */
    protected $projectDirLabel;

    public function getTitle()
    {
        return 'Проект';
    }

    public function getTabStyle()
    {
        return '-fx-padding: 1px 7px; -fx-font-weight: bold;';
    }

    public function isCloseable()
    {
        return false;
    }

    public function isDraggable()
    {
        return false;
    }

    public function open()
    {
        parent::open();

        $project = Ide::project();

        if ($project) {
            if ($project && !$this->init) {
                $this->init = true;
                UXApplication::runLater(function () use ($project) {
                    $project->trigger('makeSettings', $this);
                });
            }

            $this->projectNameLabel->text = $project->getName();
            $this->projectDirLabel->text = File::of($project->getRootDir());

            UXApplication::runLater(function () use ($project) {
                $project->trigger('updateSettings', $this);
            });
        }
    }

    public function load()
    {
        // nop.
    }

    public function save()
    {
        // nop.
    }

    /**
     * @param UXNode $node
     * @param bool $prepend
     */
    public function addSettingsPane(UXNode $node, $prepend = true)
    {
        Logger::info("Add settings pane ...");

        if (property_exists($node, 'padding')) {
            $node->padding = 10;
            $pane = $node;
        } else {
            $pane = new UXVBox();
            $pane->add($node);
            $pane->padding = 10;
        }

        if ($prepend) {
            $this->content->children->insert(2, $pane);
            $this->content->children->insert(2, new UXSeparator());
        } else {
            $this->content->add($pane);
            $this->content->add(new UXSeparator());
        }
    }

    /**
     * @return UXNode
     */
    public function makeUi()
    {
        $loader = new UXLoader();
        $ui = $loader->load(Stream::of(AbstractForm::DEFAULT_PATH . 'blocks/_ProjectTab.fxml'));

        $pane = new UXAnchorPane();
        $pane->add($ui);

        UXAnchorPane::setAnchor($ui, 10);
        $binder = new EventBinder($ui, $this);
        $binder->setLookup(function (UXNode $context, $id) {
            return $context->lookup("#$id");
        });

        $binder->load();

        $this->ui = $ui;

        $this->content = $ui->lookup('#content');
        $this->projectNameLabel = $ui->lookup('#projectNameLabel');
        $this->projectDirLabel = $ui->lookup('#projectDirLabel');

        return $pane;
    }

    public function makeLeftPaneUi()
    {
        $ui = Ide::project()->getTree()->getRoot();
        $ui->focusTraversable = false;
        UXAnchorPane::setAnchor($ui, 0);

        return $ui;
    }

    /**
     * @event changeNameButton.action
     */
    public function doChangeProjectName()
    {
        if (Ide::project()) {
            $input = UXDialog::input('Введите новое название для проекта', Ide::project()->getName());

            if ($input) {
                if (!FileUtils::validate($input)) {
                    return;
                }

                $success = Ide::project()->setName($input);

                if (!$success) {
                    UXDialog::showAndWait("Невозможно дать проекту введенное имя '$input', попробуйте другое.");
                } else {
                    $this->projectNameLabel->text = $input;
                    Ide::get()->setOpenedProject(Ide::project());
                }
            }
        }
    }

    /**
     * @event openProjectDirButton.action
     */
    public function doOpenProjectDir()
    {
        $desktop = new UXDesktop();
        $desktop->open(Ide::project()->getRootDir());
    }
}