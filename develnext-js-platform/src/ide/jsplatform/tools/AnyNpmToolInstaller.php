<?php
namespace ide\jsplatform\tools;

use ide\tool\AbstractToolInstaller;
use php\lang\Thread;
use php\util\Scanner;
use php\util\SharedValue;

class AnyNpmToolInstaller extends AbstractToolInstaller
{
    /**
     * @return mixed
     */
    public function run()
    {
        if ($this->tool->getManager()->has('npm')) {
            $version = $this->tool->getVersion() == 'default' ? '' : ('@' . $this->tool->getVersion());

            $process = $this->tool->getManager()->execute('npm', ['install', $this->tool->getName() . $version, '-g'])->start();

            $this->triggerProgress('initialize', 0);
            $this->triggerMessage('npm install ' . $this->tool->getName() . $version . ' -g');

            $done = 2;
            $callback = function () use ($process) {
                $this->triggerDone($process->getExitValue() == 0);
            };

            (new Thread(function () use ($process, $callback, &$done) {
                $scanner = new Scanner($process->getInput());

                $this->triggerProgress('installing', 10);

                while ($scanner->hasNextLine()) {
                    $this->triggerMessage($scanner->nextLine());
                }

                $done--;

                if ($done <= 0) $callback();
            }))->start();

            (new Thread(function () use ($process, $callback, &$done) {
                $scanner = new Scanner($process->getError());

                while ($scanner->hasNextLine()) {
                    $this->triggerMessage($scanner->nextLine(), 'error');
                }

                $done--;

                if ($done <= 0) $callback();
            }))->start();
        }

        return null;
    }
}