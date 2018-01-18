<?php
namespace ide\webplatform\project\behaviours\web;


use ide\IdeConfiguration;
use ide\Logger;
use php\io\IOException;
use php\lib\fs;
use php\util\Configuration;

/**
 * Class WebApplicationConfig
 * @package ide\webplatform\project\behaviours\web
 */
class WebApplicationConfig extends Configuration
{
    private $file;

    /**
     * WebApplicationConfig constructor.
     */
    public function __construct(string $file = null)
    {
        parent::__construct();

        if ($file) {
            $this->useFile($file);
        }
    }

    /**
     * @param string $file
     */
    public function useFile(string $file)
    {
        $this->file = $file;
        if (fs::isFile($file)) {
            try {
                $this->load($file);
            } catch (IOException $e) {
                Logger::warn("Failed to load application config, {$e->getMessage()}");
            }
        }
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    public function saveFile()
    {
        $this->save($this->file);
    }

    public function setServerHost(string $host)
    {
        $this->set('web.server.host', $host);
    }

    public function getServerHost(): string
    {
        return $this->get('web.server.host');
    }

    public function setServerPort(int $port)
    {
        $this->set('web.server.port', $port);
    }

    public function getServerPort(): int
    {
        return $this->getInteger('web.server.port');
    }
}