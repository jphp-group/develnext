<?php
namespace ide\project;

use ide\formats\AbstractFileTemplate;
use ide\utils\FileUtils;
use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lib\Str;
use php\util\Regex;
use php\xml\DomDocument;
use php\xml\DomElement;

/**
 * Class ProjectFile
 * @package ide\project
 */
class ProjectFile extends File
{
    /**
     * @var Project
     */
    protected $project;

    /**
     * @var int
     */
    protected $syncTime;

    /**
     * @var bool
     */
    protected $generated = false;

    /**
     * @var bool
     */
    protected $hiddenInTree = false;

    /**
     * @var AbstractFileTemplate
     */
    protected $template = null;

    /**
     * @var ProjectFile[]
     */
    protected $links = [];

    /**
     * ProjectFile constructor.
     *
     * @param Project $project
     * @param string $filename
     */
    public function __construct(Project $project, $filename)
    {
        parent::__construct($filename);

        $this->project = $project;
    }

    public function isChanged()
    {
        return $this->syncTime != parent::lastModified();
    }

    /**
     * @param string $suffix
     * @return string
     */
    public function getRelativePath($suffix = '')
    {
        $rootDir = $this->project->getRootDir() . ($suffix ? "/$suffix" : '');

        return FileUtils::relativePath($rootDir, parent::getPath());
    }

    /**
     * @return bool
     */
    public function isInRootDir()
    {
        $path = $this->getRelativePath();

        return !Str::startsWith($path, '/');
    }

    /**
     * @return int
     */
    public function getSyncTime()
    {
        return $this->syncTime;
    }

    /**
     * @param int $syncTime
     */
    public function setSyncTime($syncTime)
    {
        $this->syncTime = $syncTime;
    }

    /**
     * @return boolean
     */
    public function isHiddenInTree()
    {
        return $this->hiddenInTree;
    }

    /**
     * @param boolean $hiddenInTree
     */
    public function setHiddenInTree($hiddenInTree)
    {
        $this->hiddenInTree = $hiddenInTree;
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return !parent::exists();
    }

    /**
     * @return boolean
     */
    public function isGenerated()
    {
        return $this->generated;
    }

    /**
     * @param boolean $generated
     */
    public function setGenerated($generated)
    {
        $this->generated = $generated;
    }

    /**
     * Обновляет шаблон файла.
     *
     * @param bool $override
     */
    public function updateTemplate($override = false)
    {
        $template = $this->template;

        if ($template) {
            try {
                $content = Stream::getContents(parent::getPath());
            } catch (IOException $e) {
                $content = null;
            }

            $parent = parent::getParentFile();

            if ($parent && !$parent->exists()) {
                $parent->mkdirs();
            }

            Stream::tryAccess(parent::getPath(), function (Stream $stream) use ($content, $template, $override) {
                $template->apply(($override || $this->isGenerated()) ? null : $content, $stream);
            }, 'w+');
        }
    }

    /**
     * @param AbstractFileTemplate $template
     */
    public function applyTemplate(AbstractFileTemplate $template)
    {
        $this->template = $template;
    }

    /**
     * @param ProjectFile $file
     */
    public function addLink(ProjectFile $file)
    {
        $this->links[FileUtils::hashName($file)] = $file;
    }

    /**
     * @return ProjectFile[]
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @return string
     */
    public function getNameHash()
    {
        return FileUtils::hashName(parent::getPath());
    }

    public function getNameNoExtension()
    {
        return FileUtils::stripExtension($this->getName());
    }

    /**
     * @param $extension
     *
     * @return ProjectFile|null
     *
     */
    public function findLinkByExtension($extension)
    {
        /** @var File $link */
        foreach ($this->links as $link) {
            if (Str::endsWith($link->getPath(), ".$extension")) {
                return $link;
            }
        }

        return null;
    }

    /**
     * @param callable $callback
     */
    public function scan(callable $callback)
    {
        FileUtils::scan($this, function ($filename) use ($callback) {
            $name = FileUtils::relativePath($this, $filename);
            $callback($name, File::of($filename));
        });
    }

    public function delete()
    {
        if (parent::isDirectory()) {
            FileUtils::deleteDirectory($this);
        } else {
            parent::delete();
        }

        foreach ($this->getLinks() as $link) {
            $link->delete();
        }
    }

    public function serialize(DomElement $element, DomDocument $document)
    {
        $element->setAttribute('src', $this->getRelativePath());

        if ($this->isGenerated()) {
            $element->setAttribute('generated', $this->isGenerated());
        }

        $element->setAttribute('inRootDir', $this->isInRootDir());

        if ($this->links) {
            $domLinks = $document->createElement('links');
            $element->appendChild($domLinks);

            foreach ($this->links as $link) {
                $domLink = $document->createElement('link');
                $domLink->setAttribute('src', $link->getRelativePath());

                $domLinks->appendChild($domLink);
            }
        }
    }

    public static function unserialize(Project $project, DomElement $element)
    {
        if ($element->getAttribute('inRootDir')) {
            $file = $project->getFile($element->getAttribute('src'));
        } else {
            $file = new ProjectFile($project, $element->getAttribute('src'));
        }

        $file->setGenerated((bool)$element->getAttribute('generated'));

        /** @var DomElement $domLink */
        foreach ($element->findAll('/links/link') as $domLink) {
            $link = $project->getFile($domLink->getAttribute('src'));
            $file->addLink($link);
        }

        return $file;
    }
}