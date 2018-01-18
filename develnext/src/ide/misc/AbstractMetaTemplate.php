<?php
namespace ide\misc;
use ide\Logger;
use ide\utils\Json;
use php\format\ProcessorException;
use php\io\FileStream;
use php\io\IOException;
use php\io\Stream;
use php\lib\fs;

/**
 * Class MetaTemplate
 * @package ide\misc
 */
abstract class AbstractMetaTemplate extends AbstractEntity
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var string
     */
    protected $metaFile;

    /**
     * MetaTemplate constructor.
     * @param string $file
     */
    public function __construct(string $file = null)
    {
        parent::__construct();

        if (isset($file)) {
            $this->useFile($file);
        }
    }

    /**
     * @param string $file
     */
    public function useFile(string $file)
    {
        $this->file = $file;
        $this->metaFile = "$file.meta";
        $this->load();
    }

    abstract public function render(Stream $out);

    /**
     * Load.
     */
    public function load()
    {
        if (fs::isFile($this->metaFile)) {
            try {
                $this->loadFromFile($this->metaFile);
            } catch (ProcessorException | IOException $e) {
                Logger::warn("Failed to load $this->metaFile, {$e->getMessage()}");
            }
        }
    }

    /**
     * Save.
     */
    public function save()
    {
        fs::ensureParent($this->file);
        $this->saveToFile($this->metaFile);

        $out = new FileStream($this->file, "w+");
        try {
            $this->render($out);
        } finally {
            $out->close();
        }
    }
}