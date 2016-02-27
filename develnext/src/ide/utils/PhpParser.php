<?php
namespace ide\utils;

use ide\Logger;
use php\lang\SourceMap;
use php\lib\Char;
use php\lib\fs;
use php\lib\Str;
use php\util\Flow;
use php\util\SharedValue;
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

    /**
     * @var int
     */
    protected $contentLineCount = 0;

    /**
     * @var array
     */
    protected $sourceMapInserts = [];

    /**
     * @var SourceMap
     */
    protected $sourceMap;

    /**
     * @var string
     */
    protected $sourceMapFile;

    public function __construct($content, $sourceMapFile = null)
    {
        $this->setContent($content);

        if ($sourceMapFile) {
            $this->applySourceMapFile($sourceMapFile);
        }
    }

    /**
     * @param $file
     * @param bool $withSourceMap
     * @return PhpParser
     */
    static function ofFile($file, $withSourceMap = false)
    {
        return new PhpParser(FileUtils::get($file), $withSourceMap ? $file . '.sourcemap' : null);
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $sourceMapFile json source map
     */
    public function applySourceMapFile($sourceMapFile)
    {
        $this->sourceMapFile = $sourceMapFile;

        if (fs::isFile($sourceMapFile)) {
            $map = (array) Json::fromFile($sourceMapFile);

            if ($map) {
                $this->sourceMap = new SourceMap(null);

                foreach ($map as $cLine => $sLine) {
                    $this->sourceMap->addLine($sLine, $cLine);
                }
            }
        }
    }

    /**
     * @return SourceMap
     */
    public function getSourceMap()
    {
        $sourceMap = new SourceMap(null);

        if ($this->sourceMap) {
            foreach ($this->sourceMap->toArray() as $cLine => $sLine) {
                $sourceMap->addLine($sLine, $cLine);
            }
        }

        $sourceMap->insertLines($this->sourceMapInserts, $this->contentLineCount);

        return $sourceMap;
    }

    /**
     * @param string $toFile
     * @param bool $saveSourceMap
     */
    public function saveContent($toFile, $saveSourceMap = false)
    {
        FileUtils::put($toFile, $this->getContent());

        if ($saveSourceMap) {
            $this->saveSourceMap($toFile . '.sourcemap');
        }
    }

    /**
     * @param $file
     */
    public function saveSourceMap($file = null)
    {
        if (!$file) {
            $file = $this->sourceMapFile;
        }

        Json::toFile($file, $this->getSourceMap()->toArray());
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
        $this->sourceMapInserts = [];
        $this->contentLineCount = StrUtils::lineCount($content);
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

    public function getCodeOfMethod($class, $method)
    {
        $coord = $this->findMethod($class, $method);

        if ($coord) {
            $startLine = $coord['line'];
            $endLine = $coord['endLine'];
            $startPos = $coord['pos'];
            $endPos = $coord['endPos'];

            $content = [];
            $scanner = new Scanner($this->content);

            $line = 0;

            while ($scanner->hasNextLine()) {
                $line++;
                $scanner->nextLine();

                if ($line >= $startLine) {
                    break;
                }
            }

            while ($scanner->hasNextLine()) {
                if ($line > $endLine) {
                    break;
                }

                $one = $scanner->nextLine();

                if ($line == $startLine) {
                    $one = str::sub($one, $startPos + 1);

                    if (!trim($one)) {
                        $line++;
                        continue;
                    }
                }

                if ($line == $endLine) {
                    $one = str::sub($one, $endPos + 1);

                    if (!trim($one)) {
                        $line++;
                        continue;
                    }
                }

                $content[] = $one;
                $line++;
            }

            $minPad = 100000;

            foreach ($content as $one) {
                for ($i = 0; $i < str::length($one); $i++) {
                    if (!Char::isWhitespace($one[$i])) {
                        if ($minPad > $i) {
                            $minPad = $i;
                        }

                        break;
                    }
                }
            }

            $content = Flow::of($content);

            if ($minPad != 0) {
                $content = $content->map(function ($one) use ($minPad) {
                    return str::sub($one, $minPad);
                });
            }

            return $content->toString("\n");
        }

        return false;
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

        while ($scanner->hasNextLine()) {
            $line = $scanner->nextLine();
            $content .= $line . "\n";

            if ($i == $lineNumber) {
                $this->sourceMapInserts[] = [$lineNumber + 1, StrUtils::lineCount($text, true)];

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

        $openBraces = 0;

        $result = null;

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

                        if ($next && $next->type == 'BraceExpr' && $next->word == '(') {
                            while ($next = $tokenizer->next()) {
                                if ($next->word == '{') {
                                    $openBraces++;
                                    $result = ['line' => $next->line, 'pos' => $next->position];
                                    break;
                                }
                            }
                        }
                    }

                    break;
                default:
                    if ($result && $next->type == 'BraceExpr') {
                        switch ($next->word) {
                            case '{':
                                $openBraces++;
                                break;
                            case '}':
                                $openBraces--;
                                if ($openBraces <= 0) {
                                    $result['endLine'] = $next->line;
                                    $result['endPos'] = $next->position;
                                    return $result;
                                }

                                break;
                        }
                    }
            }

            $prev = $next;
        }

        return $result;
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

    public function findAllUseImport(SourceTokenizer $tokenizer = null)
    {
        $tokenizer = $this->getTokenizer($tokenizer);

        $result = [];

        while ($token = $tokenizer->next()) {
            if ($token->type == 'NamespaceUseStmt') {
                if ($name = $tokenizer->next()) {

                    if ($name->type == 'Name' || $name->type == 'FulledName') {
                        $done = true;

                        $item = ['line' => $token->line, 'pos' => $token->position, 0 => $name->word];

                        if ($done) {
                            $token = $tokenizer->next();

                            if ($token) {
                                $line['endLine'] = $token->line;
                                $line['endPos'] = $token->position;
                            }

                            if ($token && $token->type == 'AsSmt') {
                                if ($as = $tokenizer->next()) {
                                    $item[1] = $as->word;

                                    if ($token = $tokenizer->next()) {
                                        $line['endLine'] = $token->line;
                                        $line['endPos'] = $token->position;
                                    }
                                }
                            }

                            $result[] = $item;
                        }
                    }
                }
            }
        }

        return $result;
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
                $this->insertAfterLine($pos['line'] + $i, "use $class" . (($alias && $alias != $class) ? " as $alias; " : "; "));
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

    public function replaceOfMethod($class, $method, $code, $smart = true)
    {
        $pos = $this->findMethod($class, $method);

        if ($pos) {
            $this->removeLines($pos['line'], $pos['endLine']);

            $memory = new MemoryStream();
            $memory->write("<?\n" . $code);
            $memory->seek(0);

            $tokenizer = new SourceTokenizer($memory, '', 'UTF-8');

            if ($smart) {
                $imports = $this->findAllUseImport($tokenizer);
            }

            $lines = [str::repeat("\t", $pos['pos']) . "{"];

            $scanner = new Scanner($code, 'UTF-8');
            while ($scanner->hasNextLine()) {
                $lines[] = str::repeat("\t", $pos['pos'] + 1) . $scanner->nextLine();
            }

            $lines[] = str::repeat("\t", $pos['pos']) . "}";


            if ($smart) {
                $newLines = [];

                foreach ($imports as $import) {
                    $lines[$import['line']] = null;
                }

                foreach ($lines as $line) {
                    if ($line !== null) {
                        $newLines[] = $line;
                    }
                }

                $lines = $newLines;
            }

            $this->insertAfterLine($pos['line'] - 1, str::join($lines, "\n"));

            if ($smart) {
                $this->addUseImports($imports);
            }

            return true;
        }

        return false;
    }
}