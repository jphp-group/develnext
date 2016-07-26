<?php
namespace ide\formats\form;

use ide\settings\AbstractSettings;

class FormEditorSettings extends AbstractSettings
{
    /**
     * @return string
     */
    public function getTitle()
    {
        return "Редактор форм и сцен";
    }

    public function getMenuTitle()
    {
        return 'Настройки для "Редактора форм"';
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return "formEditor";
    }
}