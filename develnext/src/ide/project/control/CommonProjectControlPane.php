<?php
namespace ide\project\control;
use php\gui\UXNode;
use php\gui\UXLoader;
use php\gui\framework\AbstractForm;
use php\io\Stream;
use php\gui\framework\EventBinder;
use php\gui\layout\UXVBox;
use php\gui\UXLabel;
use php\gui\layout\UXAnchorPane;
use ide\Ide;
use php\gui\UXApplication;
use php\io\File;
use ide\Logger;
use php\gui\UXSeparator;
use php\gui\UXDialog;
use ide\utils\FileUtils;
use php\gui\UXDesktop;

/**
 * Class CommonProjectControlPane
 * @package ide\project\control
 */
class CommonProjectControlPane extends AbstractProjectControlPane
{
    /**
     * @var UXVBox
     */
    protected $content;

    /**
     * @var UXLabel
     */
    protected $projectNameLabel;

    /**
     * @var UXLabel
     */
    protected $projectDirLabel;

    /**
     * @var bool
     */
    protected $init = false;

    public function getName()
    {
        return "Проект";
    }

    public function getDescription()
    {
        return "Главные настройки проекта";
    }

    public function getIcon()
    {
        return 'icons/myProject16.png';
    }

    /**
     * @return UXNode
     */
    public function makeUi()
    {
        $loader = new UXLoader();
        $ui = $loader->load(Stream::of(AbstractForm::DEFAULT_PATH . 'blocks/_ProjectTab.fxml'));

        $binder = new EventBinder($ui, $this);
        $binder->setLookup(function (UXNode $context, $id) {
            return $context->lookup("#$id");
        });

        $binder->load();

        $this->content = $ui->lookup('#content');
        $this->projectNameLabel = $ui->lookup('#projectNameLabel');
        $this->projectDirLabel = $ui->lookup('#projectDirLabel');

        return $ui;
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
     * Refresh ui and pane.
     */
    public function refresh()
    {
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