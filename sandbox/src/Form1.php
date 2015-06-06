<?php

use php\gui\designer\UXDesigner;
use php\gui\framework\AbstractForm;
use php\gui\UXButton;
use php\gui\UXForm;

/**
 * Class Form1
 * @property UXButton $foobar
 */
class Form1 extends AbstractForm
{
    public function __construct(UXForm $form = null)
    {
        parent::__construct($form);

        $this->_origin->title = 'Привет';
        $this->foobar->text = 'Привет мир!';

        $designer = new UXDesigner($this->_origin->layout);
        $designer->registerNode($this->foobar);
    }
}