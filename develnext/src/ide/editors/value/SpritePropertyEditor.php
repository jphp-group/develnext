<?php
namespace ide\editors\value;

use ide\commands\CreateGameSpriteProjectCommand;
use ide\Ide;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use php\game\UXSprite;
use php\game\UXSpriteView;
use php\gui\event\UXMouseEvent;
use php\gui\UXContextMenu;
use php\gui\UXImage;
use php\gui\UXMenuItem;
use php\lib\Str;
use php\lib\String;
use php\util\Flow;

class SpritePropertyEditor extends TextPropertyEditor
{
    public function makeUi()
    {
        $result = parent::makeUi();

        $this->dialogButton->on('click', function (UXMouseEvent $e) {
            $this->showPopup($e);
        });

        return $result;
    }

    public function getCode()
    {
        return 'sprite';
    }

    protected function showPopup(UXMouseEvent $e)
    {
        $menu = new UXContextMenu();

        $menu->items->clear();

        $clearItem = new UXMenuItem('<нет спрайта>');
        $clearItem->on('action', function () {
            $this->applyValue('');
        });

        $menu->items->add($clearItem);
        $menu->items->add(UXMenuItem::createSeparator());

        $project = Ide::project();

        if ($project && $project->hasBehaviour(GuiFrameworkProjectBehaviour::class)) {
            /** @var GuiFrameworkProjectBehaviour $behaviour */
            $behaviour = $project->getBehaviour(GuiFrameworkProjectBehaviour::class);

            $manager = $behaviour->getSpriteManager();

            foreach ($manager->getSprites() as $name => $spec) {
                $item = new UXMenuItem($name);
                $image = Ide::get()->getImage($manager->getSpritePreview($name));

                if ($image) {
                    $image->preserveRatio = true;
                    $image->size = [16, 16];
                }

                $item->graphic = $image;

                $item->on('action', function () use ($name) {
                    $this->applyValue($name);
                });

                $menu->items->add($item);
            }

            $menu->items->add(UXMenuItem::createSeparator());

            $addItem = new UXMenuItem('Создать спрайт', ico('plus16'));
            $addItem->on('action', function () {
                $command = new CreateGameSpriteProjectCommand();
                if ($name = $command->onExecute()) {
                    $this->applyValue($name);
                }
            });

            $menu->items->add($addItem);
        }

        $menu->show($e->sender->form, $e->screenX, $e->screenY);
    }
}