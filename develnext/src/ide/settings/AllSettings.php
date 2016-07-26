<?php
namespace ide\settings;

class AllSettings extends AbstractSettings
{
    /**
     * @return string
     */
    public function getTitle()
    {
        return 'Общие настройки';
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return 'general';
    }

    public function isAlways()
    {
        return true;
    }
}