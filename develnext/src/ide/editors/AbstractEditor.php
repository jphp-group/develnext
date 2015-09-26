<?php
namespace ide\editors;
use ide\formats\AbstractFormat;
use ide\Ide;
use ide\project\Project;
use ide\project\ProjectIndexer;
use php\gui\layout\UXPane;
use php\gui\UXNode;
use php\lang\Thread;

/**
 * Class AbstractEditor
 * @package ide\editors
 */
abstract class AbstractEditor
{
    /** @var string */
    protected $file;

    /**
     * @var AbstractFormat
     */
    protected $format;


    public $cacheData = [];

    /**
     * AbstractEditor constructor.
     * @param string $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param AbstractFormat $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return AbstractFormat
     */
    public function getFormat()
    {
        return $this->format;
    }

    protected function reindexImpl(ProjectIndexer $indexer)
    {
        // nop.
    }

    public function reindex()
    {
        $project = Ide::get()->getOpenedProject();

        if ($project) {
            $this->reindexImpl($project->getIndexer());
        }
    }

    abstract public function load();
    abstract public function save();

    public function close()
    {
        $this->save();
        $this->reindex();
    }

    public function open()
    {
        $this->reindex();
    }

    public function getTitle()
    {
        return $this->format->getTitle($this->file);
    }

    public function getIcon()
    {
        return $this->format->getIcon();
    }

    public function getTooltip()
    {
        return null;
    }

    /**
     * @return UXNode
     */
    abstract public function makeUi();
}