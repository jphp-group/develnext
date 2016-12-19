<?php
namespace ide\javaplatform\tools;

use ide\Ide;
use ide\tool\AbstractTool;
use ide\tool\AbstractToolInstaller;
use php\lang\Process;
use php\lib\fs;
use php\lib\num;

/**
 * Class JavaTool
 * @package ide\javaplatform\tools
 */
class JavaTool extends AbstractTool
{
    /**
     * @param array $args
     * @param $workDirectory
     * @return Process
     */
    public function execute(array $args, $workDirectory = null)
    {

    }

    /**
     * @return bool
     */
    public function getName()
    {
        return 'java';
    }

    /**
     * @return bool
     */
    public function isAvailable()
    {
        if (Ide::get()->isWindows()) {
            return fs::isFile($this->getBinPath() . '/bin');
        } else {
            return fs::isFile($this->getBinPath() . '/java');
        }
    }

    /**
     * @return AbstractToolInstaller
     */
    public function setup()
    {
        return null;
    }
}