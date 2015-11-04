<?php
namespace ide\editors;

use ide\commands\NewProjectCommand;
use ide\commands\OpenProjectCommand;
use ide\forms\OpenProjectForm;
use ide\Ide;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXVBox;
use php\gui\UXLabel;
use php\gui\UXLoader;
use php\gui\UXNode;

class ProjectEditor extends AbstractEditor
{
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
        $pane = new UXVBox();

        $label = new UXLabel('В разработке.');
        $label->classes->add('dn-title');
        $pane->add($label);

        $label = new UXLabel('Здесь будут главные настройки проекта ...');
        $label->classes->add('dn-list-hint');
        $pane->add($label);

        return $pane;
    }

    public function makeLeftPaneUi()
    {
        $ui = Ide::project()->getTree()->getRoot();
        $ui->focusTraversable = false;
        UXAnchorPane::setAnchor($ui, 0);

        return $ui;
    }
}