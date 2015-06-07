<?php
namespace ide\forms;

use ide\Ide;
use php\gui\designer\UXDesigner;
use php\gui\framework\AbstractForm;
use php\gui\UXButton;
use php\gui\UXForm;

/**
 * @property UXButton button1
 * @property UXButton button2
 */
class MainForm extends AbstractForm
{
    public function show()
    {
        parent::show();

        $this->_origin->maximized = true;
    }
}