<?php
namespace ide\project\control;
use game\SpriteSpec;
use ide\commands\CreateGameSpriteProjectCommand;
use ide\commands\CreateScriptModuleProjectCommand;
use ide\editors\AbstractEditor;
use ide\editors\GameSpriteEditor;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use php\gui\UXNode;
use php\gui\layout\UXAnchorPane;
use php\lib\fs;
use php\lib\reflect;

/**
 * @package ide\project\control
 */
class SpritesProjectControlPane extends AbstractEditorsProjectControlPane
{
    public function getName()
    {
        return "Спрайты";
    }

    public function getDescription()
    {
        return "Игровая графика";
    }

    public function getIcon()
    {
        return 'icons/album16.png';
    }

    /**
     * @return mixed
     */
    protected function doAdd()
    {
        $command = new CreateGameSpriteProjectCommand();
        $command->onExecute();
    }

    /**
     * @return mixed[]
     */
    protected function getItems()
    {
        $gui = GuiFrameworkProjectBehaviour::get();
        return $gui ? $gui->getSpriteEditors() : [];
    }

    /**
     * @param GameSpriteEditor $item
     * @return mixed
     */
    protected function getBigIcon($item)
    {
        $spec = $item->getSpec();
        $gui = GuiFrameworkProjectBehaviour::get();

        if ($gui) {
            $image = $gui->getSpriteManager()->getSpritePreview($spec->name);

            if (!$image) {
                return ico('grayQuestion16')->image;
            }

            return $image;
        } else {
            return ico('grayQuestion16')->image;
        }
    }
}