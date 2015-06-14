<?php

use ide\editors\FormEditor;
use ide\Ide;

$app = new Ide();

// editors.
$app->registerEditor(FormEditor::class);

$app->launch();