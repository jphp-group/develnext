<?php
namespace ide\forms;

use ide\Ide;
use php\gui\designer\UXDesigner;
use php\gui\framework\AbstractForm;
use php\gui\UXButton;
use php\gui\UXForm;
use php\gui\UXImage;
use php\gui\UXImageView;
use php\gui\UXTab;
use php\gui\UXTabPane;

/**
 * @property UXTabPane fileTabPane
 * @property UXTabPane $projectTabs
 */
class MainForm extends AbstractForm
{
    public function show()
    {
        parent::show();

        $this->maximized = true;

        $tab = new UXTab();
        $tab->text = 'Form1';
        $tab->style = '-fx-cursor: hand;';
        $tab->closable = false;

        $editor = Ide::get()->getEditor('d:/sample.fxml');
        $editor->load();
        $tab->content = $editor->makeUi();

        $this->fileTabPane->tabs->add($tab);
    }
}