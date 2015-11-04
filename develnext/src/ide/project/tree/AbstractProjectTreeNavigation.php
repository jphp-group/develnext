<?php
namespace ide\project\tree;

use ide\Ide;
use ide\project\Project;
use ide\project\ProjectTreeItem;
use ide\utils\FileUtils;
use php\io\File;

abstract class AbstractProjectTreeNavigation
{
    /**
     * @var Project
     */
    protected $project;

    /**
     * @var string
     */
    protected $path;

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @return ProjectTreeItem
     */
    abstract public function getItems();

    /**
     * AbstractProjectTreeNavigation constructor.
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }


    public function getIcon()
    {
        return 'icons/folder16.png';
    }

    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    public function getPath()
    {
        return $this->path;
    }

    protected function getItemsByFiles(callable $filter)
    {
        $result = [];

        FileUtils::scan($this->project->getFile($this->path), function ($filename) use ($filter, &$result) {
            if (\Files::isDir($filename) ) {
                $format = Ide::get()->getFormat($filename);

                if ($format == null && $filter(null, $filename)) {
                    $result[] = $one = new ProjectTreeItem(File::of($filename)->getName(), $filename);
                    $one->getOrigin()->graphic = ico('folder16');
                }
            }
        });

        FileUtils::scan($this->project->getFile($this->path), function ($filename) use ($filter, &$result) {
            $format = Ide::get()->getFormat($filename);

            if ($format && $filter($format, $filename)) {
                $result[] = $one = new ProjectTreeItem($format->getTitle($filename), $filename);
                $one->getOrigin()->graphic = Ide::get()->getImage($format->getIcon());
            }
        });

        return $result;
    }
}