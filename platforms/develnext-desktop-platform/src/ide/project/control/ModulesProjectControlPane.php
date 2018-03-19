<?php
namespace ide\project\control;
use ide\commands\CreateScriptModuleProjectCommand;
use ide\editors\ScriptModuleEditor;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use php\gui\UXNode;
use php\gui\layout\UXAnchorPane;
use ide\editors\AbstractEditor;

/**
 * @package ide\project\control
 */
class ModulesProjectControlPane extends AbstractEditorsProjectControlPane
{
    public function getName()
    {
        return "Модули";
    }

    public function getDescription()
    {
        return "Модули и скрипты";
    }

    public function getIcon()
    {
        return 'icons/blocks16.png';
    }

    /**
     * @return AbstractEditor[]
     */
    protected function getItems()
    {
        $gui = GuiFrameworkProjectBehaviour::get();

        return $gui ? $gui->getModuleEditors() : [];
    }

    /**
     * @param ScriptModuleEditor $item
     * @return UXNode
     */
    protected function makeItemUi($item)
    {
        $box = parent::makeItemUi($item);

        if ($item->isAppModule()) {
            $box->setTitle($box->getTitle(), '-fx-font-weight: bold;');
        }

        return $box;
    }


    /**
     * @return mixed
     */
    protected function getBigIcon($item)
    {
        return 'icons/blocks32.png';
    }

    /**
     * @return mixed
     */
    protected function doAdd()
    {
        $command = new CreateScriptModuleProjectCommand();
        $command->onExecute();
    }
}