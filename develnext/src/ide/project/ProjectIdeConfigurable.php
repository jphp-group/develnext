<?php
namespace ide\project;

use ide\Ide;
use php\lib\str;

trait ProjectIdeConfigurable
{
    /**
     * @param string $key
     * @param null $def
     * @return null|string
     */
    protected function getIdeConfigValue($key, $def = null)
    {
        if ($config = $this->getIdeConfig()) {
            return $config->get($key, $def);
        }

        return null;
    }

    /**
     * @param $key
     * @param $value
     * @return null
     */
    protected function setIdeConfigValue($key, $value)
    {
        if ($config = $this->getIdeConfig()) {
            $config->set($key, $value);
        }
    }

    /**
     * @return \php\util\Configuration
     */
    protected function getIdeConfig()
    {
        if (Ide::project()) {
            $name = str::replace(get_class($this), "\\", "/") . ".conf";

            return Ide::project()->getIdeConfig($name);
        } else {
            return null;
        }
    }

    protected function saveIdeConfig()
    {
        if (Ide::project()) {
            $file = get_class($this);
            $name = str::replace(get_class($this), "\\", "/") . ".conf";

            Ide::project()->getIdeConfig($name)->saveFile();
        }
    }
}