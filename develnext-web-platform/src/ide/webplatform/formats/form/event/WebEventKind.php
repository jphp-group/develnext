<?php
namespace ide\webplatform\formats\form\event;

use ide\formats\form\event\AbstractEventKind;

/**
 * Class WebEventKind
 * @package ide\webplatform\formats\form\event
 */
class WebEventKind extends AbstractEventKind
{
    /**
     * @return array
     */
    public function getArguments()
    {
        return [
            ['framework\\core\\Event', 'e', 'null']
        ];
    }
}