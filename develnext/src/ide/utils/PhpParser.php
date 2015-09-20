<?php
namespace ide\utils;

use php\lib\Str;
use phpx\parser\SourceToken;
use phpx\parser\SourceTokenizer;
use php\io\MemoryStream;
use php\io\Stream;
use php\util\Scanner;

/**
 * Class PhpParser
 * @package ide\utils
 */
class PhpParser
{
    /**
     * @var string
     */
    protected $content = '';

    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param $from
     * @param $to
     * @param callable $lineBuilder ($line) : newLine
     */
    public function processLines($from, $to, callable $lineBuilder)
    {
        $content = "";
        $scanner = new Scanner($this->content);

        $i = 0;

        while ($scanner->hasNext()) {
            if ($i >= $from && $i <= $to) {
                $content .= $lineBuilder($scanner->nextLine()) . "\n";
            } else {
                $content .= $scanner->nextLine() . "\n";
            }

            $i++;
        }

        $this->content = $content;
    }

    /**
     * @param int $from
     * @param int $to
     */
    public function removeLines($from, $to)
    {
        $content = "";
        $scanner = new Scanner($this->content);

        $i = 0;

        while ($scanner->hasNext()) {
            if ($i < $from || $i > $to) {
                $content .= $scanner->nextLine() . "\n";
            } else {
                $scanner->nextLine();
            }

            $i++;
        }

        $this->content = $content;
    }

    /**
     * @param string $class
     * @param string $method
     * @param string $code
     * @return bool
     */
    public function insertToMethod($class, $method, $code)
    {
        $coord = $this->findMethod($class, $method);

        if ($coord) {
            $line = $coord['line'];

            $this->insertAfterLine($line, $code);
            return true;
        }

        return false;
    }

    /**
     * @param int $lineNumber
     * @param $text
     */
    public function insertAfterLine($lineNumber, $text)
    {
        $content = "";
        $scanner = new Scanner($this->content);

        $i = 0;
        $inserted = false;

        while ($scanner->hasNext()) {
            $content .= $scanner->nextLine() . "\n";

            if ($i == $lineNumber) {
                $content .= $text . "\n";
                $inserted = true;
            }

            $i++;
        }

        if (!$inserted) {
            $content .= $text . "\n";
        }

        $this->content = $content;
    }

    /**
     * @param $class
     * @param SourceTokenizer|null $tokenizer
     * @return array|null
     */
    public function findClass($class, SourceTokenizer $tokenizer = null)
    {
        $tokenizer = $this->getTokenizer($tokenizer);

        /** @var SourceToken $prev */
        $prev = null;

        while ($next = $tokenizer->next()) {
            switch ($next->type) {
                case 'ClassStmt':
                    if ($prev && $prev->type == 'StaticAccessExprToken') {
                        $prev = null;
                        continue;
                    }

                    $next = $tokenizer->next();

                    if ($next->type == 'Name' && (!$class || Str::equalsIgnoreCase($class, $next->word))) {

                        while ($next = $tokenizer->next()) {
                            if ($next->word == '{') {
                                return ['line' => $next->line, 'pos' => $next->position];
                            }
                        }
                    }

                    break;
            }

            $prev = $next;
        }

        return null;
    }

    /**
     * @param $class string if not set means any class
     * @param $methodName
     * @param SourceTokenizer|null $tokenizer
     * @return array|null
     */
    public function findMethod($class, $methodName, SourceTokenizer $tokenizer = null)
    {
        $tokenizer = $this->getTokenizer($tokenizer);

        /** @var SourceToken $prev */
        $prev = null;

        $classFound = false;

        while ($next = $tokenizer->next()) {
            switch ($next->type) {
                case 'ClassStmt':
                    if ($prev && $prev->type == 'StaticAccessExprToken') {
                        $prev = null;
                        continue;
                    }

                    $next = $tokenizer->next();

                    if ($next->type == 'Name' && (!$class || Str::equalsIgnoreCase($class, $next->word))) {
                        $classFound = true;
                        continue;
                    }

                    break;

                case 'FunctionStmt':
                    if (!$classFound) continue;

                    $next = $tokenizer->next();

                    if ($next->type == 'Name' && Str::equalsIgnoreCase($methodName, $next->word)) {
                        $next = $tokenizer->next();

                        if ($next && $next->word == '(') {
                            while ($next = $tokenizer->next()) {
                                if ($next->word == '{') {
                                    return ['line' => $next->line, 'pos' => $next->position];
                                }
                            }
                        }
                    }

                    break;
            }

            $prev = $next;
        }

        return null;
    }

    /**
     * @param SourceTokenizer|null $tokenizer
     *
     * @return array
     */
    public function findUseImports(SourceTokenizer $tokenizer = null)
    {
        $tokenizer = $this->getTokenizer($tokenizer);

        $line = 0;
        $pos = 0;

        while ($next = $tokenizer->next()) {
            switch ($next->type) {
                case 'NamespaceStmt':
                case 'NamespaceUseStmt':
                    if ($next = $tokenizer->next()) {
                        if ($next->word !== '(') {
                            $line = $next->line;
                            $pos = $next->position;
                        }
                    }

                    break;
            }
        }

        return ['line' => $line, 'pos' => $pos];
    }

    public function findUseImport($class, $alias = null)
    {
        $tokenizer = $this->getTokenizer();

        while ($token = $tokenizer->next()) {
            if ($token->type == 'NamespaceUseStmt') {
                if ($name = $tokenizer->next()) {
                    $done = $name->word == $class;

                    if ($done) {
                        if ($alias) {
                            $token = $tokenizer->next();
                            if ($token && $token->type == 'AsSmt') {
                                if ($as = $tokenizer->next()) {
                                    if ($as->word == $alias) {
                                        return $name;
                                    }
                                }
                            }
                        } else {
                            return $name;
                        }
                    }
                }
            }
        }

        return null;
    }

    public function addUseImports(array $imports)
    {
        $i = 0;
        $pos = $this->findUseImports();

        foreach ($imports as $import) {
            $class = $import[0];
            $alias = $import[1];

            if (!$this->findUseImport($class, $alias)) {
                $this->insertAfterLine($pos['line'] + $i, "use $class" . ($alias ? " as $alias;" : ";"));
                $i++;
            }
        }
    }

    public function getTokenizer(SourceTokenizer $tokenizer = null)
    {
        if ($tokenizer) {
            return $tokenizer;
        }

        $memory = Stream::of('php://memory', 'w+');
        $memory->write($this->content);
        $memory->seek(0);

        return new SourceTokenizer($memory, '', 'UTF-8');
    }

    /**
     * @param string $className
     * @param string $methodName
     * @param string $code
     * @return bool
     */
    public function appendToMethod($className, $methodName, $code)
    {
        $pos = $this->findMethod($className, $methodName);

        if ($pos) {
            $this->insertAfterLine($pos['line'], $code);
            return true;
        }

        return false;
    }
}