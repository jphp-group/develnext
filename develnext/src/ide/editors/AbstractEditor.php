<?php
namespace ide\editors;
use ide\editors\form\IdeTabPane;
use ide\formats\AbstractFormat;
use ide\Ide;
use ide\IdeConfiguration;
use ide\Logger;
use ide\project\Project;
use ide\project\ProjectIndexer;
use ide\systems\FileSystem;
use ide\utils\FileUtils;
use ide\utils\Json;
use php\gui\layout\UXPane;
use php\gui\UXNode;
use php\lang\IllegalStateException;
use php\lang\Thread;
use php\lib\reflect;
use php\lib\str;

/**
 * Class AbstractEditor
 * @package ide\editors
 */
abstract class AbstractEditor
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var int
     */
    protected $fileTime;

    /**
     * @var bool
     */
    protected $incorrectFormat = false;

    /***
     * @var bool
     */
    protected $readOnly = false;

    /**
     * @var bool
     */
    protected $tabbed = true;

    /**
     * @var AbstractFormat
     */
    protected $format;

    /**
     * @var null|IdeTabPane
     */
    protected $leftPaneUi = null;


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
     * @return IdeConfiguration
     */
    protected function getIdeConfig()
    {
        if (Ide::project()) {
            $file = Ide::project()->getAbsoluteFile($this->file);

            if ($file->isInRootDir()) {
                $name = str::replace(get_class($this), "\\", "/") . "/" . $file->getRelativePath() . ".conf";

                return Ide::project()->getIdeConfig($name);
            }

            return null;
        } else {
            return null;
        }
    }

    protected function saveIdeConfig()
    {
        if (Ide::project()) {
            $file = Ide::project()->getAbsoluteFile($this->file);

            if ($file->isInRootDir()) {
                $name = str::replace(get_class($this), "\\", "/") . "/" . $file->getRelativePath() . ".conf";

                Ide::project()->getIdeConfig($name)->save();
            }
        }
    }

    /**
     * @return bool
     */
    public function isReadOnly()
    {
        return $this->readOnly;
    }

    public function isAutoClose()
    {
        return true;
    }

    /**
     * @param bool $readOnly
     */
    public function setReadOnly($readOnly)
    {
        $this->readOnly = $readOnly;
    }

    /**
     * @return bool
     */
    public function isTabbed()
    {
        return $this->tabbed;
    }

    /**
     * @param bool $tabbed
     */
    public function setTabbed($tabbed)
    {
        $this->tabbed = $tabbed;
    }

    /**
     * @return boolean
     */
    public function isIncorrectFormat()
    {
        return $this->incorrectFormat;
    }

    public function isCorrectFormat()
    {
        return !$this->isIncorrectFormat();
    }

    /**
     * @param boolean $incorrectFormat
     */
    public function setIncorrectFormat($incorrectFormat)
    {
        if ($this->incorrectFormat == $incorrectFormat) return;

        $this->incorrectFormat = $incorrectFormat;
    }

    /**
     * @return bool
     */
    public function isCloseable()
    {
        return true;
    }

    public function isDraggable()
    {
        return true;
    }

    public function getTabStyle()
    {
        return '';
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

    /**
     * @param bool $save
     */
    public function close($save = true)
    {
        if ($save) {
            if (!$this->readOnly) {
                $this->save();
            }

            $this->reindex();
        }
    }

    public function leave()
    {
        // nop
    }

    public function open($param = null)
    {
        Logger::info("Open editor for: $this->file, param = " . json_encode($param));
        $this->reindex();
    }

    public function refresh()
    {
        // nop
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

    /**
     * Контент для левой панели главной формы Ide.
     *
     * @return IdeTabPane|UXNode
     */
    public function makeLeftPaneUi()
    {
        return null;
    }

    /**
     * @return null
     */
    public function getLeftPaneUi()
    {
        return $this->leftPaneUi;
    }

    /**
     * @param null $leftPaneUi
     * @throws IllegalStateException
     */
    public function setLeftPaneUi($leftPaneUi)
    {
        if ($this->leftPaneUi) throw new IllegalStateException();

        $this->leftPaneUi = $leftPaneUi;
    }

    public function hide()
    {
        ;
    }

    public function delete($silent = false)
    {
        FileSystem::close($this->file, true, false);

        $this->getFormat()->delete($this->file, $silent);
    }

    public function equals($what)
    {
        return $what instanceof AbstractEditor && reflect::typeOf($what) == reflect::typeOf($this)
            && FileUtils::equalNames($this->getFile(), $what->getFile());
    }
}