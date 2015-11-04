<?php
namespace ide\formats\sprite;

use ide\formats\AbstractFormat;
use ide\formats\GameSpriteFormat;
use ide\Ide;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\ProjectTreeItem;
use ide\project\tree\AbstractProjectTreeNavigation;

/**
 * Class SpriteProjectTreeNavigation
 * @package ide\formats\sprite
 */
class SpriteProjectTreeNavigation extends AbstractProjectTreeNavigation
{
    public function getName()
    {
        return 'Спрайты';
    }

    /**
     * @return ProjectTreeItem
     */
    public function getItems()
    {
        $project = $this->project;

        if ($project && $project->hasBehaviour(GuiFrameworkProjectBehaviour::class)) {
            /** @var GuiFrameworkProjectBehaviour $behaviour */
            $behaviour = $project->getBehaviour(GuiFrameworkProjectBehaviour::class);

            $manager = $behaviour->getSpriteManager();

            $result = [];

            foreach ($manager->getSprites() as $sprite) {
                $result[] = $one = new ProjectTreeItem($sprite->name, $project->getFile("{$this->path}/{$sprite->name}.sprite"));
                $one->getOrigin()->graphic = $icon = Ide::get()->getImage($manager->getSpritePreview($sprite->name));

                if ($icon) {
                    $icon->size = [16, 16];
                }
            }

            return $result;
        } else {
            return $this->getItemsByFiles(function (AbstractFormat $format = null) {
                return $format instanceof GameSpriteFormat/* or $format == null*/
                    ;
            });
        }
    }
}