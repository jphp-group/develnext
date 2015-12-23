<?php
namespace ide\editors;

use ide\Ide;
use php\gui\framework\AbstractForm;
use php\gui\framework\EventBinder;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXVBox;
use php\gui\UXDialog;
use php\gui\UXLabel;
use php\gui\UXLoader;
use php\gui\UXNode;
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

    public function open()
    {
        parent::open();

        $project = Ide::project();

        if ($project) {
            $this->projectNameLabel->text = $project->getName();
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
                if (!Ide::project()->setName($input)) {
                    UXDialog::show("Невозможно дать проекту введенное имя '$input', попробуйте другое.");
                } else {
                    $this->projectNameLabel->text = $input;
                    Ide::get()->setOpenedProject(Ide::project());
                }
            }
        }
    }
}