<?php
namespace ide\editors\argument;

/**
 * Class AudioArgumentEditor
 * @package ide\editors\argument
 */
class AudioArgumentEditor extends ResourceArgumentEditor
{
    /**
     * @return string
     */
    public function getCode()
    {
        return 'audio';
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return 'src/.data/audio';
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return 'icons/audioFile16.png';
    }

    /**
     * @return array
     */
    public function getExtensions()
    {
        return ['mp3', 'wav', 'wave', 'aif', 'aiff'];
    }

    /**
     * @return string
     */
    public function getExtensionDescription()
    {
        return "Аудио файлы";
    }

    public function getPrefixValue()
    {
        return parent::getPrefixValue() . ".data/audio/";
    }
}