<?php
namespace ide\formats\factory;

use ide\formats\AbstractFormat;
use ide\formats\AbstractFormFormat;
use ide\formats\FactoryFormat;
use ide\project\ProjectTreeItem;
use ide\project\tree\AbstractProjectTreeNavigation;
use php\lib\Str;

class FactoryProjectTreeNavigation extends AbstractProjectTreeNavigation
{
    public function getName()
    {
        return 'Фабрики';
    }

    /**
     * @return ProjectTreeItem
     */
    public function getItems()
    {
        return $this->getItemsByFiles(function (AbstractFormat $format = null) {
            return $format instanceof FactoryFormat/* || $format == null*/;
        });
    }
}