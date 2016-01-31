<?php
namespace ide\formats\form;

use ide\formats\form\event\AbstractEventKind;
use ide\Logger;
use ide\misc\EventHandlerBehaviour;
use ide\utils\FileUtils;
use ide\utils\PhpParser;
use php\io\File;
use php\io\IOException;
use php\io\MemoryStream;
use php\io\Stream;
use php\lib\Items;
use php\lib\Str;
use php\util\Regex;
use php\util\Scanner;
use phpx\parser\SourceToken;
use phpx\parser\SourceTokenizer;

/**
 * Class SourceEventManager
 * @package ide\formats\form
 */
class SourceEventManager
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var int
     */
    protected $classBeginLine;

    /**
     * @var array
     */
    protected $eventBinds = [];

    /**
     * @var SourceToken[]
     */
    protected $readTokens = [];

    /**
     * @param $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * @param string $id
     *
     * @return array
     */
    public function findBinds($id)
    {
        $result = [];

        foreach ($this->eventBinds as $bind => $info) {
            if ($id) {
                if (Str::startsWith($bind, "$id.")) {
                    $result[Str::sub($bind, Str::length($id) + 1)] = $info;
                }
            } else {
                if (!Str::contains($bind, ".")) {
                    $result[$bind] = $info;
                }
            }
        }

        return $result;
    }

    /**
     * @param $class
     * @param $method
     * @return array|null
     */
    public function findMethod($class, $method)
    {
        $parser = new PhpParser($this->loadContent());
        return $parser->findMethod($class, $method);
    }

    /**
     * @param string $id
     * @param $event
     *
     * @return array|null
     */
    public function findBind($id, $event)
    {
        if ($id) {
            return $this->eventBinds["$id.$event"];
        } else {
            return $this->eventBinds[$event];
        }
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function removeBinds($id)
    {
        $binds = $this->findBinds($id);

        $parser = new PhpParser($this->loadContent());

        $offset = 0;

        foreach ($binds as $bind) {
            $parser->removeLines($bind['eventLine'] - $offset, $bind['endLine'] - $offset);
            $offset += $bind['endLine'] - $bind['eventLine'] + 1;
        }

        $this->save($parser->getContent());
        $this->load();

        return !!$binds;
    }

    public function loadContent()
    {
        if (\Files::exists("$this->file.source")) {
            return FileUtils::get("$this->file.source");
        }

        return FileUtils::get($this->file);
    }

    public function save($content)
    {
        FileUtils::put("$this->file.source", $content);
    }

    /**
     * @param $id
     * @param $event
     *
     * @return bool
     */
    public function removeBind($id, $event)
    {
        $bind = $this->findBind($id, $event);

        if ($bind) {
            $parser = new PhpParser($this->loadContent());
            $parser->removeLines($bind['eventLine'], $bind['endLine']);

            $this->save($parser->getContent());
            return $bind;
        }

        return false;
    }

    /**
     * @param string $oldId
     * @param string $newId
     * @param array $eventsWithIdParam  e.g. ['collision']
     * @return array
     */
    public function renameBind($oldId, $newId, array $eventsWithIdParam = [])
    {
        $parser = new PhpParser($this->loadContent());

        $binds = $this->findBinds($oldId);

        $i = 0;

        $result = [];
        $methodLines = [];

        foreach ($binds as $bind) {
            $methodLines[$bind['methodLine']] = $bind;
        }

        $parser->processLines(0, 999999, function ($line) use ($oldId, $newId, $methodLines, $eventsWithIdParam, &$result, &$i) {
            $tmp = Str::trim($line);
            $tmp = Regex::of('[ ]+?')->with($tmp)->replace(' ');

            if (Str::startsWith($tmp, "* ")) {
                $regex = Regex::of('\\*[ ]{0,}\\@event[ ]+([\w\d\\_]+)\\.([\w\d\\_]+)')->with(Regex::CASE_INSENSITIVE)->with($line);

                if ($regex->find()) {
                    $id = $regex->group(1);

                    if ($id == $oldId) {
                        $line = $regex->replaceGroup(1, $newId);
                    }
                }

                if ($eventsWithIdParam) {
                    $regex = Regex::of('\\*[ ]{0,}\\@event[ ]+([\w\d\\_]+)\\.([\w\d\\_]+)\\-([a-z0-9\\_]+)')
                                ->withFlags(Regex::CASE_INSENSITIVE)
                                ->with($line);

                    if ($regex->find()) {
                        $event = $regex->group(2);
                        $paramId = $regex->group(3);

                        if ($paramId == $oldId && in_array($event, $eventsWithIdParam)) {
                            $line = $regex->replaceGroup(3, $newId);
                        }
                    }
                }
            } elseif ($methodLines[$i] && Str::startsWith($tmp, "function do" . Str::upperFirst($oldId))) {
                $k = Str::pos($line, "function do");

                $methodEnd = Str::upperFirst($newId)
                    . Str::sub($line, $k + Str::length("function do $oldId") - 1);
                $methodName = Str::upperFirst($newId) . Str::sub($line, $k + Str::length("function do $oldId") - 1, Str::pos($tmp, '(') + 1);

                $line = Str::sub($line, 0, $k)
                    . "function do" . $methodEnd;

                $item = $methodLines[$i];
                $item['newMethodName'] = "do$methodName";

                $result[] = $item;
            }

            $i++;
            return $line;
        });

        $this->save($parser->getContent());
        $this->load();
        return $result;
    }

    public function insertCodeToMethod($class, $method, $code)
    {
        $parser = new PhpParser($this->loadContent());

        if ($parser->insertToMethod($class, $method, $code)) {
            $this->save($parser->getContent());
        }
    }

    public function replaceCodeOfMethod($class, $method, $code)
    {
        $parser = new PhpParser($this->loadContent());

        if ($parser->replaceOfMethod($class, $method, $code)) {
            $this->save($parser->getContent());
        }
    }

    public function getCodeOfMethod($class, $method)
    {
        $parser = new PhpParser($this->loadContent());
        return $parser->getCodeOfMethod($class, $method);
    }

    public function addUseImports(array $imports)
    {
        if ($imports) {
            $parser = new PhpParser($this->loadContent());
            $parser->addUseImports($imports);

            $this->save($parser->getContent());
        }
    }

    /**
     * @param $id
     * @param $event
     * @param AbstractEventKind $kind
     */
    public function addBind($id, $event, AbstractEventKind $kind)
    {
        $source = "";

        Logger::info("Start adding event bind: id = $id, event = $event, kind = " . get_class($kind));

        $methodName = "do" . Str::upperFirst($id) . Str::upperFirst(Str::replace(Str::replace($event, '-', ''), '+', ''));

        $line = $this->classBeginLine;

        foreach ($this->eventBinds as $bind => $info) {
            $line = $info['endLine'];
        }

        $scanner = new Scanner($this->loadContent(), 'UTF-8');

        $i = 0;

        $bind = $id ? "$id.$event" : $event;

        $arguments = [];
        $imports = [];

        foreach ($kind->getArguments() as $arg) {
            if (sizeof($arg) > 1) {
                $className = $arg[0];

                $imports[] = [$className];

                $className = Str::split($className, '\\');
                $className = $className[sizeof($className) - 1];

                $arguments[] = $className . " $" . $arg[1];

                if ($arg[2]) {
                    $arguments[sizeof($arguments) - 1] .= " = $arg[2]";
                }
            } else {
                $arguments[] = "$" . $arg[0];
            }
        }

        $arguments = Str::join($arguments, ", ");

        while ($scanner->hasNextLine()) {
            if ($line + 1 === $i) {
                $source .= "\n\t/**\n\t * @event $bind \n\t **/\n";
                $source .= "\tfunction $methodName($arguments)\n\t{\t\n\t\t\n\t}\n";
            }

            $source .= $scanner->nextLine() . "\n";

            $i++;
        }

        if ($imports) {
            $parser = new PhpParser($source);
            $parser->addUseImports($imports);

            $source = $parser->getContent();
        }

        $this->save($source);

        Logger::info("Finish adding bind: id = $id, event = $event.");
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * ...
     */
    public function load()
    {
        $this->className = null;
        $this->eventBinds = [];
        $this->classBeginLine = -1;

        $file = $this->file;

        if (\Files::exists("$file.source")) {
            $file = "$file.source";
        }

        try {
            $tokenizer = new SourceTokenizer($file, $this->file, 'UTF-8');

            if ($this->nextTo($tokenizer, 'ClassStmt')) {
                $name = $tokenizer->next();

                $this->className = $name->word;

                $open = $this->nextTo($tokenizer, 'BraceExpr', '{');

                if ($open) {
                    $this->classBeginLine = $open->line;

                    while ($function = $this->nextTo($tokenizer, 'FunctionStmt')) {
                        $comment = $this->prevTo('Comment', 4);

                        if ($comment) {
                            $this->tryAppendEventMethod($tokenizer, $function, $comment);
                        }
                    }
                }
            }

        } catch (IOException $e) {

        } finally {
            if ($tokenizer) $tokenizer->close();
        }
    }

    protected function tryAppendEventMethod(SourceTokenizer $tokenizer, SourceToken $function, SourceToken $comment)
    {
        $meta = $comment->getMeta();

        $methodName = '';

        if ($name = $this->nextTo($tokenizer, 'Name')) {
            $methodName = $name->word;
        }

        $scanner = new Scanner($meta['comment'], 'UTF-8');

        $eventBind = null;
        while ($scanner->hasNextLine()) {
            $line = Str::trim($scanner->nextLine());

            if (Str::startsWith($line, '@event ')) {
                $eventBind = Str::trim(Str::sub($line, 7));
                break;
            }
        }

        $open = $this->nextTo($tokenizer, 'BraceExpr', '{');

        $next = $tokenizer->next();

        if (($next && $next->word == '}' && $token = $next) || ($token = $this->nextToClosedBrace($tokenizer, '{', '}'))) {
            if ($eventBind) {
                $beginPosition = $next && $next->word != '}' ? $next->position : $open->position;
                $beginLine = $next && $next->word != '}' ? $next->line : $open->line;

                if ($beginLine + 2 == $token->line && $token->word == $next->word) {
                    $beginLine += 1;
                    $beginPosition = $function->position + 1;
                }

                $this->eventBinds[$eventBind] = [
                    'className'      => $this->className,
                    'methodName'     => $methodName,
                    'methodLine'     => $function->line,
                    'eventLine'      => $comment->line,
                    'methodPosition' => $function->position,

                    'beginLine'      => $beginLine,
                    'beginPosition'  => $beginPosition,

                    'endLine'        => $token->line,
                    'endPosition'    => $token->position,
                ];
            }
        }
    }

    protected function prevTo($tokenType, $iterMax = -1, $word = null)
    {
        $len = Items::count($this->readTokens);

        $iter = 0;

        for ($i = $len - 1; $i > 0; $i--) {
            $token = $this->readTokens[$i];

            if ($token->type == $tokenType && ($word === null || $token->word == $word)) {
                return $token;
            }

            if ($iterMax > -1 && $iter >= $iterMax) {
                return null;
            }

            $iter++;
        }

        return null;
    }


    protected function nextToClosedBrace(SourceTokenizer $tokenizer, $open, $close)
    {
        $cnt = 1;

        while ($token = $tokenizer->next()) {
            if ($token->type == 'BraceExpr') {
                switch ($token->word) {
                    case $open:
                        $cnt++;
                        break;
                    case $close:
                        $cnt--;
                        break;
                }

                if ($cnt <= 0) {
                    return $token;
                }
            }
        }

        return false;
    }

    protected function nextTo(SourceTokenizer $tokenizer, $tokenType, $word = null)
    {
        while ($token = $tokenizer->next()) {
            $this->readTokens[] = $token;

            if ($token->type == $tokenType && ($word === null || $token->word == $word)) {
                return $token;
            }
        }

        return null;
    }
}