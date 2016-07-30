<?php
namespace develnext\lexer\inspector;

use develnext\lexer\Context;
use develnext\lexer\inspector\entry\ArgumentEntry;
use develnext\lexer\inspector\entry\ConstantEntry;
use develnext\lexer\inspector\entry\ExtendTypeEntry;
use develnext\lexer\inspector\entry\FunctionEntry;
use develnext\lexer\inspector\entry\MethodEntry;
use develnext\lexer\inspector\entry\TypeEntry;
use develnext\lexer\inspector\entry\TypePropertyEntry;
use develnext\lexer\SyntaxAnalyzer;
use develnext\lexer\token\ArgumentStmtToken;
use develnext\lexer\token\ClassStmtToken;
use develnext\lexer\token\ClassVarStmtToken;
use develnext\lexer\token\FunctionStmtToken;
use develnext\lexer\token\MethodStmtToken;
use develnext\lexer\token\SimpleToken;
use develnext\lexer\Tokenizer;
use ide\Ide;
use php\compress\ArchiveEntry;
use php\compress\ArchiveInputStream;
use php\framework\Logger;
use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lang\Environment;
use php\lang\JavaException;
use php\lib\arr;
use php\lib\fs;
use php\lib\str;
use php\util\Regex;
use php\util\Scanner;

/**
 * Class PHPInspector
 * @package develnext\lexer\inspector
 */
class PHPInspector extends AbstractInspector
{
    protected $typeNameRegex = '([a-z_\\x7f-\\xff][\\|\\[\\]a-z\\\\0-9_\\x7f-\\xff]+)';
    protected $typeNameRegexDyn = '([a-z_\\x7f-\\xff\\$][\\$\\|\\[\\]a-z\\\\0-9_\\x7f-\\xff]+)';
    protected $simpleTypes = ['null', 'string', 'int', 'bool', 'float', 'double', 'boolean', 'integer', 'void', 'false', 'true', 'array', 'callable', 'mixed', 'object', 'number'];
    protected $extensions = ['php'];

    public function loadSource($path)
    {
        if ($path instanceof Stream) {
            return $this->loadPhpSource($path, $path->getPath());
        }

        switch (fs::ext($path)) {
            case 'zip':
            case 'jar':
                $this->loadZipSource($path);
                return true;
            default:
                if (arr::has($this->extensions, fs::ext($path))) {
                    return $this->loadPhpSource($path, $path);
                } else {
                    return false;
                }
        }
    }

    public function unloadSource($path)
    {
        if ($path instanceof Stream) {
            return $this->loadPhpSource($path, $path, true);
        }

        switch (fs::ext($path)) {
            case 'zip':
            case 'jar':
                $this->loadZipSource($path, true);
                return true;
            default:
                if (arr::has($this->extensions, fs::ext($path))) {
                    $this->loadPhpSource($path, $path, true);
                    return true;
                } else {
                    return false;
                }
        }
    }

    /**
     * @param array $extensions
     */
    public function setExtensions($extensions)
    {
        $this->extensions = $extensions;
    }

    protected function loadZipSource($path, $unload = false)
    {
        $archive = new ArchiveInputStream('zip', $path);
        $entries = [];

        try {
            while ($entry = $archive->nextEntry()) {
                $entries[] = $entry;
            }
        } catch (IOException $e) {
            return;
        }

        $archive->close();

        $archive = new ArchiveInputStream('zip', $path);

        try {
            while ($entry = $archive->nextEntry()) {
                /** @var ArchiveEntry $entry */
                $entry = arr::shift($entries);

                if (arr::has($this->extensions, fs::ext($entry->getName()))) {
                    if (!$this->loadPhpSource($archive, $entry->getName(), $unload)) {
                    }
                }
            }

            $archive->close();
        } catch (IOException $e) {
            return;
        }
    }

    protected function loadPhpSource($path, $moduleName = null, $unload = false)
    {
        $stream = $path instanceof Stream ? $path : Stream::of($path);

        $result = ['classes' => [], 'functions' => []];

        try {
            $tokenizer = new Tokenizer(new Context($stream, $moduleName));
            $analyzer = new SyntaxAnalyzer(Environment::current(), $tokenizer);

            foreach ($analyzer->getClasses() as $class) {
                if ($unload) {
                    $this->removeType($class->getFulledName());
                } else {
                    $this->putType($t = $this->makeType($class));
                    $result['classes'][] = $t;
                }
            }

            foreach ($analyzer->getFunctions() as $function) {
                if ($unload) {
                    $this->removeFunction($function->getFulledName());
                } else {
                    $this->putFunction($f = $this->makeFunction($function));
                    $result['functions'][] = $f;
                }
            }

            return $result;
        } catch (JavaException $e) {
            if ($e->isNullPointerException() || $e->isIllegalArgumentException()) {
                Ide::get()->sendError($e, 'php-inspector');
                return false;
            }

            throw $e;
        } catch (\ParseError $e) {
            if (is_string($path)) {
                Logger::warn("Unable to load php source $moduleName");
            }

            return false;
        } finally {
            if (!($path instanceof Stream)) {
                $stream->close();
            }
        }
    }

    protected function parseClassDocType($comment, SimpleToken $owner)
    {
        $data = [];

        if (str::contains($comment, '@getters')) {
            $data['getters'] = true;
        }

        if (str::contains($comment, '@non-getters')) {
            $data['getters'] = false;
        }

        $data['content'] = $this->parseDescription($comment);
        $data['deprecated'] = str::contains($comment, '@deprecated');

        $regex = Regex::of('\\@iterator-type[ ]+' . $this->typeNameRegex, Regex::CASE_INSENSITIVE | Regex::DOTALL)->with($comment);

        if ($regex->find()) {
            $type = $regex->group(1);

            foreach (str::split($type, '|') as $one) {
                $realType = str::split($one, '[]', 2)[0];

                if (!arr::has($this->simpleTypes, $realType)) {
                    $data['iterator-type'] = SyntaxAnalyzer::getRealName($realType, $owner, 'CLASS');

                    if (str::endsWith($one, '[]')) {
                        $data['iterator-type'] .= '[]';
                    }
                } else {
                    $data['iterator-type'] = $one;
                }
            }
        }

        return $data;
    }

    protected function parseDescription($comment)
    {
        $content = '';
        $scanner = new Scanner($comment, 'UTF-8');

        $result = [];
        $lang = 'DEF';

        while ($scanner->hasNextLine()) {
            $line = $scanner->nextLine();

            if (str::startsWith($line, "--") && str::endsWith($line, "--")) {
                $result[$lang] = $content;
                $content = '';

                $lang = str::sub($line, 2, str::length($line) - 2);
                continue;
            }

            if (str::trimLeft($line)[0] != '@' && $line) {
                $content .= "$line\n";
            }
        }

        if ($content) {
            $result[$lang] = $content;
        }

        return $result;
    }

    protected function parseFunctionDocType($comment, SimpleToken $owner)
    {
        $regex = Regex::of('\\@return[ ]+' . $this->typeNameRegex, Regex::CASE_INSENSITIVE | Regex::DOTALL)->with($comment);

        $data = [];

        if ($regex->find()) {
            $type = $regex->group(1);

            foreach (str::split($type, '|') as $one) {
                $realType = str::split($one, '[]', 2)[0];

                if (!arr::has($this->simpleTypes, $realType)) {
                    $data['return'] = SyntaxAnalyzer::getRealName($realType, $owner, 'CLASS');

                    if (str::endsWith($one, '[]')) {
                        $data['return'] .= '[]';
                    }
                } else {
                    $data['return'] = $one;
                }
            }
        }

        $regex = Regex::of("\\@param[ ]+$this->typeNameRegex[ ]+\\$([a-z0-9_]+)([ ]{0,}[\\w\\d\\(\\)\\.\\,\\;\\- \\t]+)?", Regex::CASE_INSENSITIVE | Regex::DOTALL)->with($comment);

        while ($regex->find()) {
            $one = [
                'name' => $regex->group(2),
                'type' => $regex->group(1),
                'description' => str::trim($regex->group(3)),
                'optional' => false,
            ];

            if ($one['description'] == '(optional)') {
                $one['description'] = '';
                $one['optional'] = true;
            } else {
                if (str::startsWith($one['description'], '(optional)')) {
                    $one['description'] = str::trim(str::sub($one['description'], 10));
                    $one['optional'] = true;
                }
            }

            $data['params'][$regex->group(2)] = $one;
        }

        $data['non-getter'] = str::contains($comment, '@non-getter');
        $data['deprecated'] = str::contains($comment, '@deprecated');
        $data['hidden'] = str::contains($comment, '@hidden');

        $data['content'] = $this->parseDescription($comment);

        $regex = Regex::of('\\@return-dynamic[ ]+' . $this->typeNameRegexDyn, Regex::CASE_INSENSITIVE | Regex::DOTALL)->with($comment);

        if ($regex->find()) {
            $data['returnDynamic'] = $regex->group(1);
        }

        return $data;
    }

    protected function parsePropertyDocType($comment, SimpleToken $owner)
    {
        $regex = Regex::of('\\@var[ ]+' . $this->typeNameRegex, Regex::CASE_INSENSITIVE | Regex::DOTALL)->with($comment);

        $data = [];

        if ($regex->find()) {
            $type = $regex->group(1);

            foreach (str::split($type, '|') as $one) {
                if ($one) {
                    $realType = str::split($one, '[]', 2)[0];

                    if (!arr::has($this->simpleTypes, $realType)) {
                        $t = SyntaxAnalyzer::getRealName($realType, $owner, 'CLASS');

                        if (str::endsWith($one, '[]')) {
                            $t .= '[]';
                        }

                        $data['type'][$one] = $t;
                    } else {
                        $data['type'][$one] = $one;
                    }
                }
            }
        } else {
            $data['type']['mixed'] = 'mixed';
        }

        $data['hidden'] = str::contains($comment, '@hidden');
        $data['deprecated'] = str::contains($comment, '@deprecated');
        $data['content'] = $this->parseDescription($comment);

        return $data;
    }

    protected function makeFunction(FunctionStmtToken $token)
    {
        $entry = new FunctionEntry();
        $entry->name = $token->getShortName();
        $entry->fulledName = $token->getFulledName();
        $entry->token = $token;

        $entry->startLine = $token->getStartLine();
        $entry->startPosition = $token->getStartPosition();
        $entry->endLine = $token->getEndLine();
        $entry->endPosition = $token->getEndPosition();

        $data = $this->parseFunctionDocType($token->getComment(), $token);
        $entry->data = $data;

        if ($data['return']) {
            $entry->data['returnType'] = $data['return'];
        }

        foreach ($token->getArguments() as $arg) {
            $entry->arguments[$arg->getName()] = $this->makeArgument($arg);
        }

        foreach ((array) $data['params'] as $name => $info) {
            if ($arg = $entry->arguments[$name]) {
                $arg->type = $info['type'];
                $arg->optional = $arg->optional || $info['optional'];
            }
        }

        return $entry;
    }

    protected function makeType(ClassStmtToken $token)
    {
        $entry = new TypeEntry();
        $entry->token = $token;
        $entry->kind = $token->getClassType();
        $entry->name = $token->getName()->getWord();
        $entry->fulledName = $token->getFulledName();
        $entry->namespace = $token->getNamespaceName();
        $entry->final = $token->isFinal();
        $entry->abstract = $token->isAbstract();

        $entry->data = $this->parseClassDocType($token->getComment(), $token);

        $entry->startLine = $token->getStartLine();
        $entry->startPosition = $token->getStartPosition();
        $entry->endLine = $token->getEndLine();
        $entry->endPosition = $token->getEndPosition();

        if ($token->getExtendName()) {
            $entry->extends[str::lower($token->getExtendName())] = new ExtendTypeEntry($token->getExtendName());
        }

        foreach ($token->getImplementNames() as $name) {
            $entry->extends[str::lower($name)] = new ExtendTypeEntry($name, ['interface' => true]);
        }

        foreach ($token->getUseNames() as $name) {
            $entry->extends[str::lower($name)] = new ExtendTypeEntry($name, ['trait' => true]);
        }

        $entry->methods = [];
        foreach ($token->getMethods() as $method) {
            $entry->methods[str::lower($method->getShortName())] = $this->makeMethod($method, $token);
        }

        $entry->properties = [];
        foreach ($token->getProperties() as $var) {
            $entry->properties[$var->getVariable()] = $this->makeProperty($var, $token);
        }

        $entry->constants = [];
        foreach ($token->getConstants() as $const) {
            foreach ($const->getItems() as $one) {
                $entry->constants[$one->getWord()] = $e = new ConstantEntry();
                $e->name = $one->getWord();
                $e->value = $one->getExprString();
            }
        }

        return $entry;
    }

    protected function makeMethod(MethodStmtToken $token, ClassStmtToken $owner)
    {
        $entry = new MethodEntry();
        $entry->token = $token;
        $entry->name = $token->getShortName();
        $entry->fulledName = $token->getFulledName();
        $entry->abstract = $token->isAbstract();
        $entry->final = $token->isFinal();
        $entry->interfacable = $token->isInterfacable();
        $entry->static = $token->isStatic();
        $entry->modifier = $token->getModifier();

        $entry->startLine = $token->getStartLine();
        $entry->startPosition = $token->getStartPosition();
        $entry->endLine = $token->getEndLine();
        $entry->endPosition = $token->getEndPosition();

        foreach ($token->getArguments() as $arg) {
            $entry->arguments[$arg->getName()] = $this->makeArgument($arg);
        }

        $data = $this->parseFunctionDocType($token->getComment(), $owner);
        $entry->data = $data;

        if ($data['return']) {
            $entry->data['returnType'] = $data['return'];
        }

        foreach ((array) $data['params'] as $name => $info) {
            if ($arg = $entry->arguments[$name]) {
                $arg->type = $info['type'];
                $arg->optional = $arg->optional || $info['optional'];
            }
        }

        return $entry;
    }

    protected function makeProperty(ClassVarStmtToken $token, ClassStmtToken $owner)
    {
        $entry = new TypePropertyEntry();
        $entry->token = $token;
        $entry->name = $token->getVariable();
        $entry->modifier = $token->getModifier();
        $entry->value = $token->getValue() ? $token->getValue()->getExprString() : null;
        $entry->static = $token->isStatic();

        $entry->startLine = $token->getStartLine();
        $entry->startPosition = $token->getStartPosition();
        $entry->endLine = $token->getEndLine();
        $entry->endPosition = $token->getEndPosition();

        $entry->data = $this->parsePropertyDocType($token->getComment(), $owner);

        return $entry;
    }

    protected function makeArgument(ArgumentStmtToken $arg)
    {
        $e = new ArgumentEntry();
        $e->token = $arg;

        $arg->startLine = $arg->getStartLine();
        $arg->startPosition = $arg->getStartPosition();
        $arg->endLine = $arg->getEndLine();
        $arg->endPosition = $arg->getEndPosition();

        $e->name = $arg->getName();
        $e->value = $arg->getValue() ? $arg->getValue()->getExprString() : null;
        $e->type = $arg->getHintTypeClass() ? $arg->getHintTypeClass()->getWord() : str::lower($arg->getHintType());
        $e->optional = !!$arg->getValue();

        if ($arg->getHintType() == 'VARARG') {
            $e->optional = true;
            $e->type = '...';
        }

        return $e;
    }

    public function findFunction($name)
    {
        if ($name[0] == '\\') $name = str::sub($name, 1);

        return parent::findFunction(str::lower($name));
    }

    public function collectTypeData($name, $withDynamic = true)
    {
        $data = parent::collectTypeData($name, $withDynamic);

        $type = $this->findType($name, $withDynamic);

        if ($type) {
            foreach ($type->extends as $one) {
                foreach ($this->collectTypeData($one->type, $withDynamic) as $name => $value) {
                    if (!isset($data[$name])) {
                        $data[$name] = $value;
                    }
                }
            }
        }

        return $data;
    }

    public function findType($name, $withDynamic = true)
    {
        if ($name[0] == '\\') $name = str::sub($name, 1);

        return parent::findType(str::lower($name), $withDynamic);
    }

    public function findMethod(TypeEntry $type = null, $name)
    {
        if ($method = parent::findMethod($type, str::lower($name))) {
            return $method;
        }

        if ($type) {
            foreach ($type->extends as $one) {
                if ($method = $this->findMethod($this->findType($one->type), $name)) {
                    return $method;
                }
            }
        }

        return null;
    }

    public function findProperty(TypeEntry $type = null, $name)
    {
        if ($property = parent::findProperty($type, $name)) {
            return $property;
        }

        if ($type) {
            foreach ($type->extends as $one) {
                if ($property = $this->findProperty($this->findType($one->type), $name)) {
                    return $property;
                }
            }
        }

        return $property;
    }

    public function putDynamicType(TypeEntry $entry)
    {
        $this->dynamicTypes[str::lower($entry->fulledName)] = $entry;
    }

    public function putType(TypeEntry $entry)
    {
        $this->types[str::lower($entry->fulledName)] = $entry;
    }

    public function putFunction(FunctionEntry $entry)
    {
        $this->functions[str::lower($entry->fulledName)] = $entry;
    }

    public function removeType($fullName)
    {
        parent::removeType(str::lower($fullName));
    }

    public function removeFunction($fullName)
    {
        parent::removeFunction(str::lower($fullName));
    }
}