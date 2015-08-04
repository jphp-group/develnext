<?php
namespace ide\misc;

use ide\utils\FileUtils;
use php\format\ProcessorException;
use php\io\File;
use php\io\Stream;
use php\lib\Str;
use php\xml\DomDocument;
use php\xml\DomElement;
use php\xml\XmlProcessor;

/**
 * Class GradleBuildConfig
 * @package ide\misc
 */
class GradleBuildConfig
{
    /** @var File */
    protected $file;

    /** @var File */
    protected $documentFile;

    /** @var DomDocument */
    protected $document;

    /** @var XmlProcessor */
    protected $processor;

    /**
     * @var string[]
     */
    protected $defines = [];

    /**
     * @var string[]
     */
    protected $plugins = [];

    /**
     * @var string[]
     */
    protected $sourceSets = [];

    /**
     * @var string[]
     */
    protected $repositories = [];

    /**
     * @var array[]
     */
    protected $dependencies = [];

    /**
     * @var string[]
     */
    protected $codeBlocks = [];

    /**
     * @var string
     */
    protected $projectName;

    /**
     * @param string $filename path to build.gradle or build.gradle.xml
     */
    public function __construct($filename)
    {
        if (Str::endsWith($filename, "build.gradle")) {
            $this->file = File::of($filename);
            $this->documentFile = File::of("$filename.xml");
        } else {
            $this->documentFile = File::of($filename);
            $this->file = new File($this->documentFile->getParent(), "/build.gradle");
        }

        $this->processor = new XmlProcessor();

        $this->load();
        $this->update();
    }

    public function save()
    {
        /** @var DomElement $domBuild */
        $domBuild = $this->document->find('/build');

        $domBuild->setAttribute('projectName', $this->projectName);

        // -- defines
        $domDefines = $this->document->find('/build/defines');

        if (!$domDefines) {
            $domBuild->appendChild($domDefines = $this->document->createElement('defines'));
        }

        foreach ($domDefines->findAll('define') as $domDefine) { $domDefines->removeChild($domDefine); }

        foreach ($this->defines as $name => $value) {
            $domDefine = $this->document->createElement('define');
            $domDefine->setAttribute('name', $name);
            $domDefine->setAttribute('value', $value);

            $domDefines->appendChild($domDefine);
        }

        // -- repositories
        $domRepositories = $this->document->find('/build/repositories');

        if (!$domRepositories) {
            $domBuild->appendChild($domRepositories = $this->document->createElement('repositories'));
        }

        foreach ($domRepositories->findAll('repository') as $domRepository) { $domRepositories->removeChild($domRepository); }

        foreach ($this->repositories as $name => $repository) {
            $domRepository = $this->document->createElement('repository');
            $domRepository->setAttribute('name', $name);
            $domRepository->setAttribute('url', $repository);

            if ($repository instanceof File) {
                $domRepository->setAttribute('flatDir', true);
            }

            $domRepositories->appendChild($domRepository);
        }

        // -- plugins
        $domPlugins = $this->document->find('/build/plugins');

        if (!$domPlugins) {
            $domBuild->appendChild($domPlugins = $this->document->createElement('plugins'));
        }

        foreach ($domPlugins->findAll('plugin') as $domPlugin) { $domPlugins->removeChild($domPlugin); }

        foreach ($this->plugins as $plugin) {
            $domPlugin = $this->document->createElement('plugin');
            $domPlugin->setAttribute('name', $plugin);

            $domPlugins->appendChild($domPlugin);
        }

        // -- sourceSets
        $domSourceSets = $this->document->find('/build/sourceSets');

        if (!$domSourceSets) {
            $domBuild->appendChild($domSourceSets = $this->document->createElement('sourceSets'));
        }

        foreach ($domSourceSets->findAll('sourceSet') as $domSourceSet) { $domSourceSets->removeChild($domSourceSet); }

        foreach ($this->sourceSets as $name => $sourceSet) {
            $domSourceSet = $this->document->createElement('sourceSet', [
                '@name' => $name,
                'value' => is_array($sourceSet) ? $sourceSet : [$sourceSet],
            ]);

            $domSourceSets->appendChild($domSourceSet);
        }

        // -- dependencies
        $domDependencies = $this->document->find('/build/dependencies');

        if (!$domDependencies) {
            $domBuild->appendChild($domDependencies = $this->document->createElement('dependencies'));
        }

        foreach ($domDependencies->findAll('dependency') as $domDependency) { $domDependencies->removeChild($domDependency); }

        foreach ($this->dependencies as $hash => $dep) {
            $domDependency = $this->document->createElement('dependency', [
                '@artifactId' => $dep[1]
            ]);

            if ($dep[0]) {
                $domDependency->setAttribute('groupId', $dep[0]);
            }

            if ($dep[2]) {
                $domDependency->setAttribute('version', $dep[2]);
            }

            $domDependencies->appendChild($domDependency);
        }

        try {
            FileUtils::put($this->documentFile, $this->processor->format($this->document));
            $stream = Stream::of($this->file, 'w+');

            $this->writePlugins($stream);

            $this->writeDefines($stream);
            $this->writeRepositories($stream);
            $this->writeSourceSets($stream);

            $this->writeDependencies($stream);

            $this->writeCodeBlocks($stream);
        } finally {
            if ($stream) $stream->close();
        }

        $this->codeBlocks = [];
    }

    public function load()
    {
        if ($this->documentFile->isFile()) {
            try {
                $this->document = $this->processor->parse(Stream::getContents($this->documentFile));
                $this->validate();
            } catch (ProcessorException $e) {
                $this->document = $this->processor->createDocument();
            }
        } else {
            $this->document = $this->processor->createDocument();
        }

        // -- plugins
        /** @var DomElement $domPlugin */
        foreach ($this->document->findAll('/build/plugins/plugin') as $domPlugin) {
            $this->addPlugin($domPlugin->getAttribute('name'));
        }

        // -- dependencies
        /** @var DomElement $domDependency */
        foreach ($this->document->findAll('/build/dependencies/dependency') as $domDependency) {
            $artifactId = $domDependency->getAttribute('artifactId');
            $groupId = $domDependency->hasAttribute('groupId') ? $domDependency->getAttribute('groupId') : null;
            $version = $domDependency->hasAttribute('version') ? $domDependency->getAttribute('version') : null;

            $this->setDependency($artifactId, $groupId, $version);
        }

        // -- defines
        /** @var DomElement $domDefine */
        foreach ($this->document->findAll('/build/defines/define') as $domDefine) {
            $this->setDefine($domDefine->getAttribute('name'), $domDefine->getAttribute('value'));
        }

        // -- repositories
        /** @var DomElement $domRepository */
        foreach ($this->document->findAll("/build/repositories/repository") as $domRepository) {
            $value = $domRepository->getAttribute('url');

            if ($domRepository->getAttribute('flatDir')) {
                $value = File::of($value);
            }

            $this->repositories[$domRepository->getAttribute('name')] = $value;
        }

        // -- sourceSets
        /** @var DomElement $domSourceSet */
        foreach ($this->document->findAll('/build/sourceSets/sourceSet') as $domSourceSet) {
            $model = $domSourceSet->toModel();

            $this->sourceSets[$model['@name']] = $model['value'];
        }
    }

    public function removePlugin($plugin)
    {
        $name = Str::split($plugin, ':')[0];
        unset($this->plugins[$name]);
    }

    public function addPlugin($plugin)
    {
        $name = Str::split($plugin, ':')[0];
        $this->plugins[$name] = $plugin;
    }

    public function addRepository($name, $value = null)
    {
        $this->repositories[$name] = $value === null ? $name : $value;
    }

    public function setDefine($name, $value)
    {
        $this->defines[$name] = $value;
    }

    public function setSourceSet($name, $value)
    {
        $this->sourceSets[$name] = $value;
    }

    public function removeDependency($artifactId, $group = null)
    {
        $hash = "$group:$artifactId";

        unset($this->dependencies[$hash]);
    }

    public function setDependency($artifactId, $group = null, $version = null)
    {
        $hash = "$group:$artifactId";

        $this->dependencies[$hash] = [
            $group, $artifactId, $version,
        ];
    }

    /**
     * @return array[]
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    protected function validate()
    {
        if (!$this->document->find('/build')) {
            throw new ProcessorException("Invalid project configuration!");
        }
    }

    protected function update()
    {
        $build = $this->document->find('/build');

        $isNew = false;

        if (!$build) {
            $build = $this->document->createElement('build');
            $this->document->appendChild($build);

            $isNew = true;
        }

        if ($isNew) {
            $this->save();
        }
    }

    protected function writeDefines(Stream $stream)
    {
        if ($this->defines) {
            $stream->write("\n");

            foreach ($this->defines as $name => $value) {
                $stream->write("$name = $value\n");
            }

            $stream->write("\n");
        }
    }

    protected function writePlugins(Stream $stream)
    {
        if ($this->plugins) {
            $stream->write("\n");

            $stream->write("plugins {\n");

            foreach ($this->plugins as $plugin) {
                list($id, $version) = Str::split($plugin, ':');

                if ($version) {
                    $stream->write("id '$id' version '$version'\n");
                } else {
                    $stream->write("id '$id'\n");
                }
            }
            $stream->write("}\n");

            $stream->write("\n");
        }
    }

    protected function writeSourceSets(Stream $stream)
    {
        if ($this->sourceSets) {
            $stream->write("\n");

            $stream->write("sourceSets {\n");

            foreach ($this->sourceSets as $name => $value) {
                if (!is_array($value)) {
                    $value = [$value];
                }

                $stream->write("\t$name = ['" . Str::join($value, "', '") . "']\n");
            }

            $stream->write("}\n");
        }
    }

    protected function writeRepositories(Stream $stream)
    {
        if ($this->repositories) {
            $stream->write("\n");

            $stream->write("repositories {\n");

            foreach ($this->repositories as $name => $repository) {
                if ($name == $repository) {
                    $stream->write("\t$name()\n");
                } elseif ($repository instanceof File) {
                    $dir = FileUtils::normalizeName("$repository");
                    $stream->write("\tflatDir { dirs '$dir' }\n");
                } else {
                    $stream->write("\tmaven { url '$repository' }\n");
                }
            }

            $stream->write("}\n");
        }
    }

    protected function writeDependencies(Stream $stream)
    {
        if ($this->dependencies) {
            $stream->write("\n");

            $stream->write("dependencies {\n");

            foreach ($this->dependencies as $hash => $dep) {
                if ($dep[0] && $dep[1] && $dep[2]) {
                    $stream->write("\tcompile '" . Str::join($dep, ":") . "'\n");
                } else {
                    $stream->write("\tcompile name: '" . $dep[1] . "'\n");
                }
            }

            $stream->write("}\n");
        }
    }

    protected function writeCodeBlocks(Stream $stream)
    {
        if ($this->codeBlocks) {
            $stream->write("\n\n");

            foreach ($this->codeBlocks as $code) {
                $stream->write($code);
                $stream->write("\n\n");
            }
        }
    }

    public function appendCodeBlock($name, $code)
    {
        $this->codeBlocks[$name] = $code;
    }

    public function removeCodeBlock($name)
    {
        unset($this->codeBlocks[$name]);
    }
}