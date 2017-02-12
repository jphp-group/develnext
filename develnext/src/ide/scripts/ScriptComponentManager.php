<?php
namespace ide\scripts;

use ide\Ide;
use ide\utils\FileUtils;
use ide\utils\Json;
use php\format\ProcessorException;
use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lang\IllegalStateException;
use php\lib\fs;
use php\lib\Str;
use php\xml\DomElement;
use php\xml\XmlProcessor;

/**
 * Class ScriptComponentManager
 * @package ide\scripts
 */
class ScriptComponentManager
{
    /**
     * @var File[]
     */
    protected $modules = [];

    /**
     * @var ScriptComponentContainer[]
     */
    protected $components = [];

}