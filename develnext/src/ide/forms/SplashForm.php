<?php
namespace ide\forms;

use ide\Ide;
use ide\Logger;
use ide\systems\SplashTipSystem;
use php\gui\event\UXEvent;
use php\gui\framework\AbstractForm;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXApplication;
use php\gui\UXImage;
use php\gui\UXImageArea;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\io\IOException;
use php\io\Stream;
use php\lang\Thread;
use php\lang\ThreadPool;
use php\lib\str;

/**
 * @property UXLabel $version
 * @property UXLabel $accountNameLabel
 * @property UXAnchorPane $accountAvatarImage
 * @property UXHBox $accountPane
 * @property UXLabel $tip
 */
class SplashForm extends AbstractIdeForm
{
    protected function init()
    {
        Logger::debug("Init form ...");

        $this->centerOnScreen();

        $versionCode = $this->_app->getConfig()->get('app.versionCode');
        $this->version->text = $this->_app->getVersion();

        if ($versionCode) {
            $this->versionCode->text = str::upperFirst($versionCode);

            $codeImg = new UXImageArea(new UXImage('res://.data/img/code/' . $versionCode . '.png'));
            $codeImg->stretch = true;
            $codeImg->smartStretch = true;
            $codeImg->size = [64, 64];
            $codeImg->position = [690 - 64 - 14, 14];

            $this->add($codeImg);
        }

        $name = Ide::get()->getUserConfigValue('splash.name');
        $avatar = Ide::get()->getUserConfigValue('splash.avatar');

        if (!$name) {
            $this->accountPane->hide();
        } else {
            $this->accountNameLabel->text = $name;

            $image = new UXImageArea(Ide::get()->getImage('noAvatar.jpg')->image);
            $image->stretch = true;
            $image->smartStretch = true;
            $image->centered = true;
            $image->proportional = true;
            UXAnchorPane::setAnchor($image, 0);

            $this->accountAvatarImage->add($image);

            if ($avatar) {
                try {
                    $image->image = new UXImage(Stream::of($avatar));
                } catch (IOException $e) {
                    Logger::error("Unable to load splash account image, {$e->getMessage()}");
                }
            }
        }

        waitAsync(7000, function() {
            if ($this->_app->getMainForm()->visible) {
                $this->hide();
            }
        });

        Ide::get()->on('start', function () {
            Ide::accountManager()->on('update', function ($data) {
                Ide::service()->media()->getImageAsync($data['avatar'], function ($file) {
                    Ide::get()->setUserConfigValue('splash.avatar', $file);
                });

                Ide::get()->setUserConfigValue('splash.name', $data['name']);
            }, __CLASS__);
            Ide::accountManager()->updateAccount();
        }, __CLASS__);
    }

    /**
     * @param UXEvent $e
     * @event tip.click
     */
    public function doTipClick(UXEvent $e)
    {
        $this->tip->text = SplashTipSystem::get(Ide::get()->getLanguage()->getCode());
        $e->consume();
    }

    /**
     * @event show
     */
    public function doShow()
    {
        $this->tip->text = SplashTipSystem::get(Ide::get()->getLanguage()->getCode());

        if (Ide::get()->isDevelopment() && Ide::get()->isWindows()) {
            if ($this->opacity > 0.9) {
                $this->opacity = 0.05;
            } else {
                $this->opacity = 1;
            }
        }

        uiLater(function () {
            $this->toFront();
        });
    }

    /**
     * @event click
     */
    public function hide()
    {
        parent::hide();
    }
}