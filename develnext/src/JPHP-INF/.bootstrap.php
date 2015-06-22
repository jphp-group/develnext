<?php

use ide\editors\FormEditor;
use ide\formats\FormFormat;
use ide\Ide;
use php\gui\UXDialog;

function dump($arg)
{
    ob_start();

        var_dump($arg);
        $str = ob_get_contents();

    ob_end_clean();

    UXDialog::show($str);
}

$app = new Ide();
$app->registerAll();
$app->launch();