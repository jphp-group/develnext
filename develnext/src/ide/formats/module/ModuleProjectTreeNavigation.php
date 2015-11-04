<?php
namespace ide\formats\module;

use ide\formats\AbstractFormat;
use ide\formats\ScriptModuleFormat;
use ide\project\ProjectTreeItem;
use ide\project\tree\AbstractProjectTreeNavigation;

class ModuleProjectTreeNavigation extends AbstractProjectTreeNavigation
{
    public function getName()
    {
        return 'Модули';
    }

    /**
     * @return ProjectTreeItem
     */
    public function getItems()
    {
        return $this->getItemsByFiles(function (AbstractFormat $format = null) {
            return $format instanceof ScriptModuleFormat/* || $format == null*/;
        });
    }
}