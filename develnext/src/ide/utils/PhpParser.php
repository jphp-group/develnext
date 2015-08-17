<?php
namespace ide\utils;

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
                    $line = $next->line;
                    $pos = $next->position;

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
}