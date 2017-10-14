<?php

use ide\editors\FormEditor;
use ide\formats\FormFormat;
use ide\Ide;
use ide\IdeClassLoader;
use ide\Logger;
use ide\systems\IdeSystem;
use php\gui\UXDialog;
use php\lang\System;

$cache = true;//!IdeSystem::isDevelopment();

if (System::getProperty('develnext.noCodeCache')) {
    $cache = false;
}

//$cache = true; //  TODO delete it.

$loader = new IdeClassLoader($cache, IdeSystem::getOwnLibVersion());
$loader->register(true);

IdeSystem::setLoader($loader);

if (!IdeSystem::isDevelopment()) {
    Logger::setLevel(Logger::LEVEL_INFO);
}

$app = new Ide();
$app->addStyle('/.theme/style.css');
$app->launch();

function _($code, ...$args) {
    static $l10n;

    if (!$l10n) {
        $l10n = Ide::get()->getL10n();
    }

    return $l10n->get($code, ...$args);
}

function dump($arg)
{
    ob_start();

        var_dump($arg);
        $str = ob_get_contents();

    ob_end_clean();

    UXDialog::showAndWait($str);
}

/**
 * @param $name
 * @return \php\gui\UXImageView
 */
function ico($name)
{
    return Ide::get()->getImage("icons/$name.png");
}