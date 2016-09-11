<?php
namespace ide\formats\form;

use ide\formats\AbstractFormat;
use ide\formats\AbstractFormFormat;
use ide\project\ProjectTreeItem;
use ide\project\tree\AbstractProjectTreeNavigation;
use php\lib\Str;

class FormProjectTreeNavigation extends AbstractProjectTreeNavigation
{
    public function getName()
    {
        return 'Формы';
    }

    /**
     * @return ProjectTreeItem
     */
    public function getItems()
    {
        return $this->getItemsByFiles(function (AbstractFormat $format = null) {
            return $format instanceof AbstractFormFormat/* || $format == null*/;
        });
    }
}