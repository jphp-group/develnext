<?php
namespace ide\commands;

use develnext\bundle\game2d\Game2DBundle;
use Dialog;
use Files;
use ide\editors\AbstractEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\formats\GameSpriteFormat;
use ide\formats\ScriptModuleFormat;
use ide\forms\BuildProgressForm;
use ide\forms\InputMessageBoxForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\project\behaviours\BundleProjectBehaviour;
use ide\project\behaviours\GradleProjectBehaviour;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\systems\FileSystem;
use ide\utils\FileUtils;
use php\gui\UXDialog;
use php\io\File;
use php\lang\Process;
use php\lib\Str;
use php\time\Time;
use php\util\Regex;

class CreateGameSpriteProjectCommand extends AbstractMenuCommand
{
    public function getName()
    {
        return 'Новый спрайт';
    }

    public function getIcon()
    {
        return 'icons/picture16.png';
    }

    public function getCategory()
    {
        return 'create';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $ide = Ide::get();
        $project = $ide->getOpenedProject();

        if ($project) {
            $name = $ide->getRegisteredFormat(GameSpriteFormat::class)->showCreateDialog();

            if ($name !== null) {
                $name = str::trim($name);

                if (!FileUtils::validate($name)) {
                    return null;
                }

                /** @var GuiFrameworkProjectBehaviour $guiBehaviour */
                $guiBehaviour = $project->getBehaviour(GuiFrameworkProjectBehaviour::class);

                if ($guiBehaviour->getSpriteManager()->get($name)) {
                    Dialog::error('Спрайт с таким названием уже существует в проекте');
                    $this->onExecute();
                    return null;
                }

                $file = $guiBehaviour->createSprite($name);
                FileSystem::open($file);

                return $name;
            }
        }
    }

    public function onBeforeShow($item, AbstractEditor $editor = null)
    {
        parent::onBeforeShow($item, $editor);

        $bundle = BundleProjectBehaviour::get();

        $item->enabled = $bundle && $bundle->hasBundleInAnyEnvironment(Game2DBundle::class);
    }
}