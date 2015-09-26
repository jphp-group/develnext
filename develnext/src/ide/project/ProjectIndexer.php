<?php
namespace ide\project;
use ide\utils\FileUtils;
use ide\utils\Json;
use php\format\ProcessorException;
use php\io\IOException;

/**
 * Class ProjectIndexer
 * @package ide\project
 */
class ProjectIndexer
{
    /**
     * @var Project
     */
    protected $project;

    /**
     * @var array
     */
    protected $index = [];

    /**
     * ProjectIndexer constructor.
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;

        $this->project->on('save', [$this, 'save']);
        $this->project->on('load', [$this, 'load']);
    }

    public function getIndexFile()
    {
        return "{$this->project->getIdeDir()}/index.json";
    }

    public function load()
    {
        try {
            $this->index = Json::fromFile($this->getIndexFile());
        } catch (IOException $e) {
            $this->index = [];
        } catch (ProcessorException $e) {
            $this->index = [];
        }
    }

    public function save()
    {
        try {
            Json::toFile($this->getIndexFile(), $this->index);
        } catch (IOException $e) {
            ;
        }
    }

    public function clear()
    {
        $this->index = [];
    }

    public function isValid()
    {
        return !!$this->index;
    }

    protected function key($file)
    {
        return FileUtils::hashName($this->project->getAbsoluteFile($file)->getRelativePath());
    }

    /**
     * @param $file
     */
    public function indexOf($file)
    {
        return $this->index[$this->key($file)];
    }

    public function get($file, $key)
    {
        return $this->index[$this->key($file)][$key];
    }

    public function remove($file, $key)
    {
        unset($this->index[$this->key($file)][$key]);
        return $this;
    }

    public function push($file, $key, $value)
    {
        $item =& $this->index[$this->key($file)][$key];

        if ($item && !is_array($item)) {
            $item = [$item];
        }

        $item[] = $value;
        return $this;
    }

    public function pushAll($file, $key, array $value)
    {
        foreach ($value as $el) {
            $this->push($file, $key, $el);
        }

        return $this;
    }

    /**
     * @param $file
     * @param $key
     * @param $value
     * @return $this
     */
    public function set($file, $key, $value)
    {
        $this->index[$this->key($file)][$key] = $value;
        return $this;
    }
}