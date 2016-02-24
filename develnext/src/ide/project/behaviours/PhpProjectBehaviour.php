<?php
namespace ide\project\behaviours;
use ide\project\AbstractProjectBehaviour;
use ide\project\ProjectFile;
use ide\systems\WatcherSystem;
use ide\utils\FileUtils;
use ide\utils\PhpParser;
use php\io\File;
use php\lib\fs;
use php\lib\str;

/**
 * Class PhpProjectBehaviour
 * @package ide\project\behaviours
 */
class PhpProjectBehaviour extends AbstractProjectBehaviour
{
    const SOURCES_DIRECTORY = 'src/app';

    /**
     * @var array
     */
    protected $globalUseImports = [];

    /**
     * ...
     */
    public function inject()
    {
        $this->project->on('open', [$this, 'doOpen']);
        $this->project->on('compile', [$this, 'doCompile']);
    }

    public function doOpen()
    {
    }

    public function doCompile($env, callable $log = null)
    {
        if ($this->globalUseImports) {
            $imports = [];

            foreach ($this->globalUseImports as $import) {
                $imports[] = [$import];
            }

            FileUtils::scan($this->project->getFile(self::SOURCES_DIRECTORY), function ($filename) use ($imports, $log) {
                if (str::endsWith($filename, '.php')) {
                    $phpParser = new PhpParser(FileUtils::get($filename));

                    $phpParser->addUseImports($imports);

                    if ($log) {
                        $filename = fs::normalize($filename);
                        $log(":import use '$filename'");
                    }

                    FileUtils::put($filename, $phpParser->getContent());
                }
            });
        }
    }

    public function clearGlobalUseImports()
    {
        $this->globalUseImports = [];
    }

    public function addGlobalUseImport($class)
    {
        $this->globalUseImports[$class] = $class;
    }
}