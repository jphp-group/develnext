<?php
namespace ide\misc;

use ide\utils\FileUtils;
use ide\utils\StrUtils;
use php\format\ProcessorException;
use php\io\File;
use php\io\Stream;
use php\lib\arr;
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

    /**
     * @deprecated
     * @var File
     */
    protected $documentFile;

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

        //$this->load();
       // $this->update();
    }

    public function save()
    {
        try {
            //FileUtils::put($this->documentFile, StrUtils::removeEmptyLines($this->processor->format($this->document)));
            $stream = Stream::of($this->file, 'w+');

            $this->writePlugins($stream);

            $this->writeDefines($stream);
            $this->writeRepositories($stream);
            $this->writeSourceSets($stream);

            $this->writeDependencies($stream);

            $this->writeCodeBlocks($stream);

            FileUtils::put(File::of($this->file)->getParent() . "/settings.gradle", "rootProject.name = '{$this->projectName}'");
        } finally {
            if ($stream) $stream->close();
        }

        $this->codeBlocks = [];
    }

    public function load()
    {
    }

    /**
     * @return string
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * @param string $projectName
     */
    public function setProjectName($projectName)
    {
        $this->projectName = $projectName;
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

    public function addSourceSet($name, $value)
    {
        if (isset($this->sourceSets[$name])) {
            if (!is_array($this->sourceSets[$name])) {
                $value = $this->sourceSets[$name];

                $this->sourceSets[$name] = [$value => $value];
            }
        } else {
            $this->sourceSets[$name] = [];
        }

        $this->sourceSets[$name][$value] = $value;
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

    /*protected function update()
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
    } */

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
                    $stream->write("\tid '$id' version '$version'\n");
                } else {
                    $stream->write("\tid '$id'\n");
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