<?php

use php\gui\UXApplication;
use php\io\Stream;

define('DEVELNEXT_PROJECT_DEBUG', true);

try {
    Stream::putContents("application.pid", UXApplication::getPid());
} catch (\php\io\IOException $e) {
    exit(1);
}