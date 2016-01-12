<?php
namespace ide\commands;

use ide\account\api\ServiceResponse;
use ide\editors\AbstractEditor;
use ide\forms\SharedProjectDetailForm;
use ide\forms\ShareProjectForm;
use ide\Ide;
use ide\Logger;
use ide\misc\AbstractCommand;
use ide\ui\Notifications;
use php\gui\UXApplication;
use php\gui\UXClipboard;
use php\gui\UXSeparator;
use php\lib\str;
use script\TimerScript;

/**
 * Class SaveProjectCommand
 * @package ide\commands
 */
class ShareProjectCommand extends AbstractProjectCommand
{
    /**
     * @var string
     */
    static public $lastCheckUid;

    /**
     * @var string
     */
    static public $lastCheckUidWithAnonymous;

    /**
     * @var bool
     */
    static protected $busy = false;

    /**
     * ShareProjectCommand constructor.
     */
    public function __construct()
    {
        Ide::get()->on('start', function () {
            $func = function () {
                self::$lastCheckUidWithAnonymous = null;
                self::setLastCheckUid(null);
            };
            Ide::accountManager()->on('login', $func);
            Ide::accountManager()->on('logout', $func);
        }, __CLASS__);

        $timer = new TimerScript(2000, true, function () {
            if (!Ide::get()->isIdle() && !self::$busy) {
                $text = str::trim(UXClipboard::getText());

                $prefix = Ide::service()->getEndpoint() . "project/";

                if(str::startsWith($text, $prefix)) {
                    $uid = str::sub($text, str::length($prefix));

                    $notAlreadyChecked = self::$lastCheckUid != $uid;
                    $notSelfProject = Ide::project() ? Ide::project()->getIdeServiceConfig()->get('projectArchive.uid') != $uid : true;

                    Logger::info(
                        "Check shared project from clipboard, uid = $uid, alreadyChecked = " . ($notAlreadyChecked ? 'false' : 'true') .
                        ", selfProject = " . ($notSelfProject ? 'false' : 'true')
                    );

                    if ($uid && $notAlreadyChecked && $notSelfProject) {
                        if (!Ide::accountManager()->isAuthorized()) {
                            if (self::$lastCheckUidWithAnonymous != $uid) {
                                self::$lastCheckUidWithAnonymous = $uid;

                                Notifications::show(
                                    'Обнаружен проект',
                                    'Мы обнаружили ссылку на проект, но вам нужно войти в свой аккаунт, чтобы открыть проект',
                                    'INFORMATION'
                                );
                            }

                            return;
                        }

                        self::$lastCheckUid = $uid;

                        Ide::service()->projectArchive()->getAsync($uid, function (ServiceResponse $response) use ($uid) {
                            if ($response->isSuccess()) {
                                self::$busy = true;

                                UXApplication::runLater(function () use ($response) {
                                    Notifications::show('Обнаружен проект', 'Мы обнаружили ссылку на общедоступный проект, вы можете его открыть.', 'INFORMATION');
                                    $dialog = new SharedProjectDetailForm($response->data()['uid']);
                                    $dialog->showAndWait();

                                    self::$busy = false;
                                });
                            } else {
                                Logger::error("Unable to get project, uid = $uid, {$response->toLog()}");
                            }
                        });
                    }
                }
            }
        });

        $timer->start();
    }

    /**
     * @param string $lastCheckUid
     */
    public static function setLastCheckUid($lastCheckUid)
    {
        self::$lastCheckUid = $lastCheckUid;
    }

    public function getName()
    {
        return 'Поделиться проектом';
    }

    public function getIcon()
    {
        return 'icons/upload16.png';
    }

    public function getAccelerator()
    {
        return 'Ctrl + Alt + S';
    }

    public function withBeforeSeparator()
    {
        return true;
    }

    public function isAlways()
    {
        return true;
    }

    public function makeMenuItem()
    {
        $item = parent::makeMenuItem();
        $item->disable = true;
        $item->accelerator = $this->getAccelerator();

        Ide::service()->bind('privateEnable', function () use ($item) { $item->disable = false; });
        Ide::service()->bind('privateDisable', function () use ($item) { $item->disable = true; });

        return $item;
    }

    public function makeUiForHead()
    {
        $UXButton = $this->makeGlyphButton();

        $UXButton->enabled = false;

        Ide::service()->bind('privateEnable', function () use ($UXButton) { $UXButton->enabled = true; });
        Ide::service()->bind('privateDisable', function () use ($UXButton) { $UXButton->enabled = false; });

        return [new UXSeparator('VERTICAL'), $UXButton];
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $project = Ide::get()->getOpenedProject();

        if ($project) {
            $project->save();

            UXApplication::runLater(function () {
                $dialog = new ShareProjectForm();
                $dialog->showAndWait();
            });
        }
    }
}