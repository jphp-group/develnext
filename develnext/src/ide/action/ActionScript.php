<?php
namespace ide\action;
use ide\utils\FileUtils;
use ide\utils\PhpParser;
use php\lib\Str;
use php\util\Flow;
use php\xml\DomDocument;
use php\xml\DomElement;
use php\xml\XmlProcessor;

/**
 * Class ActionContainer
 * @package ide\editors\action
 */
class ActionScript
{
    /**
     * @var DomDocument
     */
    protected $document;

    /**
     * @var ActionManager
     */
    protected $manager;

    /**
     * ActionContainer constructor.
     * @param DomDocument $document
     * @param ActionManager $manager
     */
    public function __construct(DomDocument $document = null, ActionManager $manager = null)
    {
        $this->manager = $manager ?: ActionManager::get();
        $this->document = $document ?: (new XmlProcessor())->createDocument();
    }

    public function load($xmlFile)
    {
        $this->document = (new XmlProcessor())->parse(FileUtils::get($xmlFile));
    }

    public function save($xmlFile)
    {
        FileUtils::put($xmlFile, (new XmlProcessor())->format($this->document));
    }

    public function compile($file, $outputFile = null)
    {
        if (!$outputFile) {
            $outputFile = $file;
        }

        $phpParser = new PhpParser(FileUtils::get($file));

        $imports = Flow::of([]);

        /** @var DomElement $domClass */
        foreach ($this->document->findAll('/root/class') as $domClass) {
            $className = $domClass->getAttribute('name');

            /** @var DomElement $domMethod */
            foreach ($domClass->findAll("/root/class[@name='$className']/method") as $domMethod) {
                $methodName = $domMethod->getAttribute('name');

                $code = '';

                foreach ($domMethod->findAll('*') as $domAction) {
                    $action = $this->manager->buildAction($domAction);

                    $code .= "\t\t";
                    $code .= $action->convertToCode();
                    $code .= ";\n";

                    $imports = $imports->append($action->imports());
                }

                $code = Str::sub($code, 0, Str::length($code) - 1); // remove "\n"

                $phpParser->appendToMethod($className, $methodName, $code);
            }
        }

        $imports = $imports->withKeys()->toArray();

        if ($imports) {
            $phpParser->addUseImports($imports);
        }

        FileUtils::put($outputFile, $phpParser->getContent());
    }
}