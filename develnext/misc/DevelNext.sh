#!/bin/sh

tools/jre/bin/java -Dfile.encoding=UTF-8 -Ddevelnext.launcher=root -Xms256m -Xmx1280m -cp "./lib/*" php.runtime.launcher.Launcher $*