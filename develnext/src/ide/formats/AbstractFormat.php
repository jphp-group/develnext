<?php
namespace ide\formats;

use ide\editors\AbstractEditor;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\project\Project;
use ide\project\ProjectFile;
use php\io\File;
use php\lang\IllegalStateException;
use php\lang\NotImplementedException;
use php\lib\fs;

/**
 * Class AbstractFormat
 * @package ide\formats
 */
abstract class AbstractFormat
{
    /**
     * @var AbstractFormat[]
     */
    protected $requireFormats = [];

    public function getIcon()
    {
        return null;
    }

    public function getTitle($path)
    {
        return File::of($path)->getName();
    }

    /**
     * @param AbstractFormat $format
     */
    protected function requireFormat(AbstractFormat $format)
    {
        $this->requireFormats[get_class($format)] = $format;
    }

    /**
     * @return AbstractFormat[]
     */
    public function getRequireFormats()
    {
        return $this->requireFormats;
    }

    public function delete($path, $silent = false)
    {
        if (fs::isDir($path)) {
            fs::clean($path);
        }

        fs::delete($path);
    }

    /**
     * @param $file
     * @return AbstractEditor
     */
    abstract public function createEditor($file);

    /**
     * @return null TODO
     */
    public function createCreator()
    {
        return null;
    }

    /**
     * @param Project $project
     * @param $file
     * @param array $options
     * @throws IllegalStateException
     * @return ProjectFile
     */
    public function createBlank(Project $project, $file, array $options)
    {
        throw new IllegalStateException("Unable to create blank file");
    }

    /**
     * @return AbstractCommand|null
     */
    public function createBlankCommand()
    {
        return null;
    }

    /**
     * @param $file
     * @return bool
     */
    abstract public function isValid($file);

    /**
     * @param $any
     * @return mixed
     */
    abstract public function register($any);

    /**
     * @param string $source
     */
    public function registerInternalList($source)
    {
        $ones = Ide::get()->getInternalList($source);

        foreach ($ones as $one) {
            $this->register(new $one);
        }
    }

    /**
     * @param string $source
     */
    public function unregisterInternalList($source)
    {
        $ones = Ide::get()->getInternalList($source);

        foreach ($ones as $one) {
            $this->unregister(new $one);
        }
    }

    /**
     * @param $any
     * @throws NotImplementedException
     */
    public function unregister($any)
    {
        throw new NotImplementedException();
    }
}