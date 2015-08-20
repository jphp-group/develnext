<?php

use ide\editors\FormEditor;
use ide\formats\FormFormat;
use ide\Ide;
use php\gui\UXDialog;

$app = new Ide();
$app->launch();

function dump($arg)
{
    ob_start();

        var_dump($arg);
        $str = ob_get_contents();

    ob_end_clean();

    UXDialog::show($str);
}

/**
 * @param $name
 * @return \php\gui\UXImageView
 */
function ico($name)
{
    return Ide::get()->getImage("icons/$name.png");
}