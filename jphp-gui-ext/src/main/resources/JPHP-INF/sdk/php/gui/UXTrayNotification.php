<?php
namespace php\gui;

/**
 * Class UXTrayNotification
 * @package php\gui
 */
class UXTrayNotification
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $message;

    /**
     * @var string INFORMATION, NOTICE, WARNING, SUCCESS, ERROR, CUSTOM
     */
    public $notificationType = 'NOTICE';

    /**
     * @var string FADE, SLIDE, POPUP
     */
    public $animationType = 'FADE';

    /**
     * @var string BOTTOM_RIGHT, BOTTOM_LEFT, TOP_LEFT, TOP_RIGHT
     */
    public $location = 'BOTTOM_RIGHT';

    /**
     * @var UXImage
     */
    public $image;

    /**
     * @var UXImage
     */
    public $trayIcon;

    /**
     * @param string $title
     * @param string $message
     * @param string $type
     */
    public function __construct($title = '', $message = '', $type = 'NOTICE')
    {
    }

    /**
     * @param int $delay in millis
     */
    public function show($delay = 5000)
    {
    }

    /**
     * ...
     */
    public function showAndWait()
    {
    }

    public function hide()
    {
    }
}