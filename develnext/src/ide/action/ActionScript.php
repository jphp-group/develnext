<?php
namespace ide\action;
use ide\utils\FileUtils;
use ide\utils\PhpParser;
use php\format\ProcessorException;
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
        try {
            $this->document = (new XmlProcessor())->parse(FileUtils::get($xmlFile));
        } catch (ProcessorException $e) {
            $this->document = (new XmlProcessor())->createDocument();
        }
    }

    public function save($xmlFile)
    {
        FileUtils::put($xmlFile, (new XmlProcessor())->format($this->document));
    }


    /**
     * @param Action[] $actions
     */
    public static function calculateLevels(array $actions)
    {
        $level = 0;

        /** @var Action $prevAction */
        $prevAction = null;

        foreach ($actions as $action) {
            $action->setLevel($level);

            if ($action->getType()->isCloseLevel() || ($prevAction && $prevAction->getType()->isAppendSingleLevel())) {
                $level -= 1;
            }

            if ($action->getType()->isAppendMultipleLevel() || $action->getType()->isAppendSingleLevel()) {
                $level += 1;

                if ($action->getType()->isAppendMultipleLevel()) {
                    $action->setLevel($level);
                }
            }

            $prevAction = $action;
        }
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
                $actions = [];

                foreach ($domMethod->findAll('*') as $domAction) {
                    $action = $this->manager->buildAction($domAction);
                    $actions[] = $action;

                    $imports = $imports->append($action->imports());
                }

                ActionScript::calculateLevels($actions);

                /** @var Action $action */
                foreach ($actions as $action)  {
                    $type = $action->getType();

                    if ($type->isAppendSingleLevel()) {
                        $code .= "\n";
                    }

                    $code .= "\t\t";

                    $level = $action->getLevel();

                    if ($type->isCloseLevel() || $type->isAppendMultipleLevel()) {
                        $level -= 1;
                    }

                    $code .= Str::repeat("\t", $level);

                    $code .= $action->convertToCode();

                    if ($type->isAppendMultipleLevel() || $type->isAppendSingleLevel() || $type->isCloseLevel()) {
                        $code .= "\n";

                        if ($type->isCloseLevel()) {
                            $code .= "\n";
                        }
                    } else {
                        $code .= ";\n";
                    }
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