<?php
namespace script;

use php\concurrent\TimeoutException;
use php\desktop\SystemTray;
use php\desktop\TrayIcon;
use php\framework\Logger;
use php\gui\event\UXMouseEvent;
use php\gui\framework\AbstractScript;
use php\gui\UXApplication;
use php\gui\UXForm;
use php\gui\UXImage;
use php\io\IOException;
use php\io\Stream;
use php\jsoup\Connection;
use php\jsoup\ConnectionResponse;
use php\jsoup\Document;
use php\jsoup\Jsoup;
use php\lang\IllegalArgumentException;
use php\lang\Thread;
use php\lib\arr;
use php\lib\str;
use php\mail\Email;
use php\mail\EmailBackend;
use php\util\Scanner;

class SystemTrayScript extends AbstractScript
{
    /**
     * @var string
     */
    protected $_visible;

    /**
     * @var bool
     */
    protected $_alwaysShowing;

    /**
     * @var string
     */
    protected $_icon;

    /**
     * @var string
     */
    protected $_tooltip;

    /**
     * @var TrayIcon
     */
    protected $_trayIcon;

    /**
     * @var bool
     */
    protected $_manualExit = false;

    /**
     * @param $target
     * @return mixed
     */
    protected function applyImpl($target)
    {
        $this->makeTrayIcon();

        if ($target instanceof UXForm) {
            $target->on('close', function () {
                if (!$this->_alwaysShowing) {
                    $this->hide();
                }
            }, __CLASS__);
        }
    }

    protected function makeTrayIcon()
    {
        if ($this->_trayIcon) {
            SystemTray::remove($this->_trayIcon);
        }

        if ($this->_icon) {
            try {
                $this->_trayIcon = new TrayIcon(new UXImage('res://' . $this->_icon));
            } catch (IOException $e) {
                Logger::error("Unable to initialize tray, {$e->getMessage()}");
                return null;
            }
        } elseif ($this->_context instanceof UXForm && $this->_context->icons[0] instanceof UXImage) {
            $this->_trayIcon = new TrayIcon($this->_context->icons[0]);
        } else {
            Logger::warn("Unable to initialize tray, icon is not set");
            return null;
        }

        $this->_trayIcon->imageAutoSize = true;
        $this->_trayIcon->tooltip = $this->_tooltip;

        $this->_trayIcon->on('click', function (UXMouseEvent $event) {
            $this->trigger('click', $event);
        });

        $this->_trayIcon->on('mouseMove', function (UXMouseEvent $event) {
            $this->trigger('mouseMove', $event);
        });

        $this->_trayIcon->on('mouseEnter', function (UXMouseEvent $event) {
            $this->trigger('mouseEnter', $event);
        });

        $this->_trayIcon->on('mouseExit', function (UXMouseEvent $event) {
            $this->trigger('mouseExit', $event);
        });

        if ($this->_visible) {
            SystemTray::add($this->_trayIcon);
        }

        return $this->_trayIcon;
    }

    /**
     * @param string $icon
     * @return null
     */
    public function setIcon($icon)
    {
        $this->_icon = $icon;

        if (!$this->_trayIcon) {
            $this->makeTrayIcon();
        } else {
            if ($icon) {
                if ($icon instanceof UXImage) {
                    $this->_trayIcon->image = $icon;
                    return;
                }

                try {
                    $this->_trayIcon->image = new UXImage('res://' . $icon);
                } catch (IOException $e) {
                    Logger::error("Cannot change tray icon, {$e->getMessage()}");
                    return null;
                }
            } elseif ($this->_context instanceof UXForm && $this->_context->icons[0] instanceof UXImage) {
                $this->_trayIcon = new TrayIcon($this->_context->icons[0]);
            } else {
                Logger::warn("Cannot change tray icon, empty value is not allowed");
            }
        }
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->_icon;
    }

    /**
     * @return string
     */
    public function getVisible()
    {
        return $this->_visible;
    }

    /**
     * @param string $visible
     */
    public function setVisible($visible)
    {
        $this->_visible = $visible;

        if ($this->_trayIcon) {
            if ($visible) {
                SystemTray::add($this->_trayIcon);
            } else {
                SystemTray::remove($this->_trayIcon);
            }
        }
    }

    public function isSupported()
    {
        return SystemTray::isSupported();
    }

    /**
     * @return string
     */
    public function getTooltip()
    {
        return $this->_tooltip;
    }

    /**
     * @param string $tooltip
     */
    public function setTooltip($tooltip)
    {
        $this->_tooltip = $tooltip;

        if ($this->_trayIcon) {
            $this->_trayIcon->tooltip = $tooltip;
        }
    }

    /**
     * Show tray icon.
     */
    public function show()
    {
        $this->setVisible(true);
    }

    /**
     * Hide tray icon.
     */
    public function hide()
    {
        $this->setVisible(false);
    }

    public function free()
    {
        parent::free();
        $this->hide();
    }


    /**
     * @return boolean
     */
    public function getManualExit()
    {
        return $this->_manualExit;
    }

    /**
     * @param boolean $manualExit
     */
    public function setManualExit($manualExit)
    {
        $this->_manualExit = $manualExit;
        UXApplication::setImplicitExit(!$manualExit);
    }

    /**
     * @return boolean
     */
    public function getAlwaysShowing()
    {
        return $this->_alwaysShowing;
    }

    /**
     * @param boolean $alwaysShowing
     */
    public function setAlwaysShowing($alwaysShowing)
    {
        $this->_alwaysShowing = $alwaysShowing;
    }
}