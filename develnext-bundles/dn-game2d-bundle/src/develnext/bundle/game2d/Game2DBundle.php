<?php
namespace develnext\bundle\game2d;

use ide\bundle\AbstractBundle;
use ide\bundle\AbstractJarBundle;
use ide\formats\form\elements\GamePaneFormElement;
use ide\formats\GuiFormFormat;
use ide\Ide;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\Project;

class Game2DBundle extends AbstractJarBundle
{
    function getName()
    {
        return "Игровой движок 2D";
    }

    function getDescription()
    {
        return "Пакет для создания простых 2D игр.";
    }

    public function isAvailable(Project $project)
    {
        return $project->hasBehaviour(GuiFrameworkProjectBehaviour::class);
    }

    public function onAdd(Project $project, AbstractBundle $owner = null)
    {
        parent::onAdd($project, $owner);

        $format = Ide::get()->getRegisteredFormat(GuiFormFormat::class);

        if ($format) {
            $format->register(new GamePaneFormElement());
            $format->register(new SpriteViewFormElement());
            $format->register(new GameBackgroundFormElement());

            $format->register(new GamePaneFormElementTag());
            $format->register(new GameBackgroundFormElementTag());
            $format->register(new SpriteViewFormElementTag());
        }
    }

    public function onRemove(Project $project, AbstractBundle $owner = null)
    {
        parent::onRemove($project, $owner);

        $format = Ide::get()->getRegisteredFormat(GuiFormFormat::class);

        if ($format) {
            $format->unregister(new GamePaneFormElement());
            $format->unregister(new SpriteViewFormElement());
            $format->unregister(new GameBackgroundFormElement());

            $format->unregister(new GamePaneFormElementTag());
            $format->unregister(new GameBackgroundFormElementTag());
            $format->unregister(new SpriteViewFormElementTag());
        }
    }
}