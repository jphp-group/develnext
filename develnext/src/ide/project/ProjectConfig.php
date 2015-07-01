<?php
namespace ide\project;
use Exception;
use ide\Ide;
use php\io\Stream;
use php\lang\System;
use php\time\Time;
use php\xml\DomDocument;
use php\xml\DomElement;
use php\xml\XmlProcessor;
use php\io\File;

/**
 *  .dnproject
 *
 * Class ProjectConfig
 * @package ide\project
 */
class ProjectConfig
{
    /**
     * @var string
     */
    protected $rootDir;

    /**
     * @var string
     */
    protected $projectName;

    /**
     * @var DomDocument
     */
    protected $document;

    /**
     * @var XmlProcessor
     */
    protected $processor;

    /**
     * @var string
     */
    protected $configPath;

    /**
     * ProjectConfig constructor.
     *
     * @param string $rootDir
     * @param string $projectName
     */
    public function __construct($rootDir, $projectName)
    {
        $this->processor = new XmlProcessor();

        $this->rootDir = $rootDir;
        $this->projectName = $projectName;

        $this->configPath = $configPath = "$rootDir/$projectName.dnproject";

        $this->reload();
        $this->update();
    }

    public function save()
    {
        $parentFile = File::of($this->configPath)->getParentFile();

        if ($parentFile) {
            $parentFile->mkdirs();
        }

        Stream::tryAccess($this->configPath, function (Stream $stream) {
            $this->processor->formatTo($this->document, $stream);
        }, 'w+');
    }

    public function reload()
    {
        if (File::of($this->configPath)->isFile()) {
            $this->document = $this->processor->parse(Stream::getContents($this->configPath));
            $this->validate();
        } else {
            $this->document = $this->processor->createDocument();
        }
    }

    /**
     * @return string
     */
    public function getConfigPath()
    {
        return $this->configPath;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getProperty($name)
    {
        return $this->document->get("/project/@$name");
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setProperty($name, $value)
    {
        $this->document->getDocumentElement()->setAttribute($name, $value);
    }

    /**
     * @return null|Time
     */
    public function getCreatedAt()
    {
        $createdAt = $this->getProperty('createdAt');

        return $createdAt ? new Time($createdAt) : null;
    }

    /**
     * @return null|Time
     */
    public function getUpdatedAt()
    {
        $updatedAt = $this->getProperty('updatedAt');

        return $updatedAt ? new Time($updatedAt) : null;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->getProperty('author');
    }

    /**
     * @return string
     */
    public function getAuthorOS()
    {
        return $this->getProperty('authorOS');
    }

    /**
     * @return string
     */
    public function getIdeVersion()
    {
        return $this->getProperty('ideVersion');
    }

    /**
     * @return string
     */
    public function getIdeName()
    {
        return $this->getProperty('ideName');
    }

    /**
     * @param ProjectFile[] $files
     */
    public function setProjectFiles(array $files)
    {
        $domFiles = $this->document->find('/project/files');

        if (!$domFiles) {
            $domFiles = $this->document->createElement('files');
            $this->document->find('/project')->appendChild($domFiles);
        }

        foreach ($domFiles->findAll('/file') as $domFile) {
            $domFiles->removeChild($domFile);
        }

        foreach ($files as $file) {
            $domFile = $this->document->createElement('file');
            $file->serialize($domFile, $this->document);

            $domFiles->appendChild($domFile);
        }
    }

    /**
     * @param AbstractProjectBehaviour[] $behaviours
     */
    public function setBehaviours(array $behaviours)
    {
        $domBehaviours = $this->document->find('/project/behaviours');

        if (!$domBehaviours) {
            $domBehaviours = $this->document->createElement('behaviours');
            $this->document->find('/project')->appendChild($domBehaviours);
        }

        foreach ($domBehaviours->findAll('/behaviour') as $domBehavior) {
            $domBehaviours->removeChild($domBehavior);
        }

        foreach ($behaviours as $behaviour) {
            $domBehavior = $this->document->createElement('behaviour');
            $domBehavior->setAttribute('class', get_class($behaviour));

            $behaviour->serialize($domBehavior, $this->document);

            $domBehaviours->appendChild($domBehavior);
        }
    }

    /**
     * @param Project $project
     *
     * @return ProjectFile[]
     */
    public function createFiles(Project $project)
    {
        $files = [];

        /** @var DomElement $domFile */
        foreach ($this->document->findAll('/project/files/file') as $domFile) {
            $files[] = ProjectFile::unserialize($project, $domFile);
        }

        return $files;
    }

    /**
     * @param Project $project
     *
     * @return AbstractProjectBehaviour[]
     */
    public function createBehaviours(Project $project)
    {
        /** @var DomElement $domBehaviour */
        foreach ($this->document->findAll('/project/behaviours/behaviour') as $domBehaviour) {
            $class = $domBehaviour->getAttribute('class');

            $behaviour = new $class();

            if ($behaviour instanceof AbstractProjectBehaviour) {
                $behaviour = $project->register($behaviour);
                $behaviour->unserialize($domBehaviour);
            }
        }
    }

    protected function update()
    {
        $project = $this->document->find('/project');

        $isNew = false;

        if (!$project) {
            $project = $this->document->createElement('project');
            $this->document->appendChild($project);

            $project->setAttribute('author', System::getProperty('user.name'));
            $project->setAttribute('authorOS', System::getProperty('os.name'));

            $project->setAttribute('createdAt', Time::millis());

            $isNew = true;
        }

        $project->setAttribute('ideVersion', Ide::get()->getVersion());
        $project->setAttribute('ideName', Ide::get()->getName());
        $project->setAttribute('updatedAt', Time::millis());

        if ($isNew) {
            $this->save();
        }
    }

    /**
     * @throws Exception
     */
    protected function validate()
    {
        if (!$this->document->find('/project')) {
            throw new Exception("Invalid project configuration!");
        }
    }
}