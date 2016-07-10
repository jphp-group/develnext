<?php
namespace develnext\bundle\game2d;

use action\Collision;
use game\Jumping;
use ide\action\ActionManager;
use ide\behaviour\IdeBehaviourDatabase;
use ide\bundle\AbstractBundle;
use ide\bundle\AbstractJarBundle;
use ide\formats\form\elements\GameBackgroundFormElement;
use ide\formats\form\elements\GamePaneFormElement;
use ide\formats\form\elements\SpriteViewFormElement;
use ide\formats\form\tags\GameBackgroundFormElementTag;
use ide\formats\form\tags\GamePaneFormElementTag;
use ide\formats\form\tags\SpriteViewFormElementTag;
use ide\formats\GuiFormFormat;
use ide\Ide;
use ide\library\IdeLibraryBundleResource;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\Project;
use php\desktop\Runtime;
use php\game\event\UXCollisionEvent;

class Game2DBundle extends AbstractJarBundle
{
    function getName()
    {
        return "2D Game";
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
            $format->registerInternalList('.dn/bundle/game2d/formComponents');
        }

        if ($bDatabase = IdeBehaviourDatabase::get()) {
            $bDatabase->registerInternalList('.dn/bundle/game2d/behaviours');
        }

        if ($aManager = ActionManager::get()) {
            $aManager->registerInternalList('.dn/bundle/game2d/actionTypes');
        }
    }

    public function onRemove(Project $project, AbstractBundle $owner = null)
    {
        parent::onRemove($project, $owner);

        $format = Ide::get()->getRegisteredFormat(GuiFormFormat::class);

        if ($format) {
            $format->unregisterInternalList('.dn/bundle/game2d/formComponents');
        }

        if ($bDatabase = IdeBehaviourDatabase::get()) {
            $bDatabase->unregisterInternalList('.dn/bundle/game2d/behaviours');
        }

        if ($aManager = ActionManager::get()) {
            $aManager->unregisterInternalList('.dn/bundle/game2d/actionTypes');
        }
    }

    public function onRegister(IdeLibraryBundleResource $resource)
    {
        parent::onRegister($resource);

        Runtime::addJar($resource->getPath() . "/dyn4j.jar");
        Runtime::addJar($resource->getPath() . "/jphp-game-ext.jar");
    }


    public function getUseImports()
    {
        return [
            UXCollisionEvent::class,

            Collision::class,
            Jumping::class,
        ];
    }
}