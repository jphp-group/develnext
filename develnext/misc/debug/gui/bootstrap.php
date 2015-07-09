<?php

use php\gui\framework\Application;
use php\lang\System;

$app = Application::get();

$mainForm = $app->getMainForm();

if (!$mainForm->alwaysOnTop) {
    $mainForm->alwaysOnTop = true;
    $mainForm->alwaysOnTop = false;
}

$mainForm->requestFocus();