#!/bin/bash

echo "-----------------------"
echo "Start DevelNext ..."
echo "-----------------------"

realpath() {
    [[ $1 = /* ]] && echo "$1" || echo "$PWD/${1#./}"
}

APP_HOME=$(dirname "$(realpath "$0")")

echo "App Home = $APP_HOME"

JAVA_HOME="$APP_HOME/tools/jre"
JAVA_BIN="$JAVA_HOME/bin/java"


cd "$APP_HOME"

OUTPUT=$(exec "$JAVA_BIN" -jar "$APP_HOME/DevelNext.jar")

echo $OUTPUT
echo ""
echo "---------------------"
echo "DevelNext has been started."