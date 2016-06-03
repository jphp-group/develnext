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
use php\compress\ArchiveEntry;
use php\compress\ArchiveInputStream;
use php\io\File;
use php\io\Stream;
use php\lang\Environment;
use php\lib\arr;
use php\lib\fs;
use php\lib\str;
use php\util\Regex;

/**
 * Class PHPInspector
 * @package develnext\lexer\inspector
 */
class PHPInspector extends AbstractInspector
{
    protected $typeNameRegex = '([a-z_\\x7f-\\xff][\\|\\[\\]a-z0-9_\\x7f-\\xff]+)';
    protected $simpleTypes = ['null', 'string', 'int', 'bool', 'float', 'double', 'boolean', 'integer', 'void', 'false', 'true', 'array', 'callable', 'mixed'];
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
                    $this->loadPhpSource($path, $path);
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

    protected function loadZipSource($path)
    {
        $archive = new ArchiveInputStream('zip', $path);
        $entries = [];
        while ($entry = $archive->nextEntry()) {
            $entries[] = $entry;
        }
        $archive->close();


        $archive = new ArchiveInputStream('zip', $path);

        while ($entry = $archive->nextEntry()) {
            /** @var ArchiveEntry $entry */
            $entry = arr::shift($entries);

            if (fs::ext($entry->getName()) == 'php') {
                $this->loadPhpSource($archive, $entry->getName());
            }
        }

        $archive->close();
    }

    protected function loadPhpSource($path, $moduleName = null)
    {
        $stream = $path instanceof Stream ? $path : Stream::of($path);

        try {
            $tokenizer = new Tokenizer(new Context($stream, $moduleName));
            $analyzer = new SyntaxAnalyzer(Environment::current(), $tokenizer);

            foreach ($analyzer->getClasses() as $class) {
                $this->putType($this->makeType($class, $analyzer));
            }

            foreach ($analyzer->getFunctions() as $function) {
                $this->putFunction($this->makeFunction($function, $analyzer));
            }

        } catch (\ParseError $e) {
            return false;
        } finally {
            if (!($path instanceof Stream)) {
                $stream->close();
            }
        }

        return true;
    }

    protected function parseFunctionDocType($comment, SimpleToken $owner)
    {
        $regex = Regex::of('\\@return[ ]+' . $this->typeNameRegex, Regex::CASE_INSENSITIVE | Regex::DOTALL)->with($comment);

        $data = [];

        if ($regex->find()) {
            $type = $regex->group(1);

            foreach (str::split($type, '|') as $one) {
                if (!arr::has($this->simpleTypes, $one)) {
                    $data['return'] = SyntaxAnalyzer::getRealName($one, $owner, 'CLASS');
                } else {
                    $data['return'] = $one;
                }
            }
        }

        $regex = Regex::of("\\@param[ ]+$this->typeNameRegex \\$([a-z0-9_]+)", Regex::CASE_INSENSITIVE | Regex::DOTALL)->with($comment);

        while ($regex->find()) {
            $data['params'][$regex->group(2)] = [
                'name' => $regex->group(2),
                'type' => $regex->group(1),
                'description' => '',
            ];
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
                    if (!arr::has($this->simpleTypes, $one)) {
                        $data['type'][$one] = SyntaxAnalyzer::getRealName($one, $owner, 'CLASS');
                    } else {
                        $data['type'][$one] = $one;
                    }
                }
            }
        } else {
            $data['type']['mixed'] = 'mixed';
        }

        return $data;
    }

    protected function makeFunction(FunctionStmtToken $token, SyntaxAnalyzer $analyzer)
    {
        $entry = new FunctionEntry();
        $entry->name = $token->getShortName();
        $entry->fulledName = $token->getFulledName();

        $data = $this->parseFunctionDocType($token->getComment(), $token);
        if ($data['return']) {
            $entry->data['returnType'] = $data['return'];
        }

        foreach ($token->getArguments() as $arg) {
            $entry->arguments[$arg->getName()] = $this->makeArgument($arg);
        }

        return $entry;
    }

    protected function makeType(ClassStmtToken $token)
    {
        $entry = new TypeEntry();
        $entry->name = $token->getName()->getWord();
        $entry->fulledName = $token->getFulledName();
        $entry->namespace = $token->getNamespaceName();
        $entry->final = $token->isFinal();
        $entry->abstract = $token->isAbstract();

        if ($token->getExtendName()) {
            $entry->extends[] = new ExtendTypeEntry($token->getExtendName());
        }

        foreach ($token->getImplementNames() as $name) {
            $entry->extends[] = new ExtendTypeEntry($name);
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
        $entry->name = $token->getShortName();
        $entry->fulledName = $token->getFulledName();
        $entry->abstract = $token->isAbstract();
        $entry->final = $token->isFinal();
        $entry->interfacable = $token->isInterfacable();
        $entry->static = $token->isStatic();
        $entry->modifier = $token->getModifier();

        foreach ($token->getArguments() as $arg) {
            $entry->arguments[$arg->getName()] = $this->makeArgument($arg);
        }

        $data = $this->parseFunctionDocType($token->getComment(), $owner);
        if ($data['return']) {
            $entry->data['returnType'] = $data['return'];
        }

        foreach ((array) $data['params'] as $name => $info) {
            if ($arg = $entry->arguments[$name]) {
                $arg->type = $info['type'];
            }
        }

        return $entry;
    }

    protected function makeProperty(ClassVarStmtToken $token, ClassStmtToken $owner)
    {
        $entry = new TypePropertyEntry();
        $entry->name = $token->getVariable();
        $entry->modifier = $token->getModifier();
        $entry->value = $token->getValue() ? $token->getValue()->getExprString() : null;
        $entry->static = $token->isStatic();

        $entry->data = $this->parsePropertyDocType($token->getComment(), $owner);

        return $entry;
    }

    protected function makeArgument(ArgumentStmtToken $arg)
    {
        $e = new ArgumentEntry();

        $e->name = $arg->getName();
        $e->value = $arg->getValue() ? $arg->getValue()->getExprString() : null;
        $e->type = $arg->getHintTypeClass() ? $arg->getHintTypeClass()->getWord() : $arg->getHintType();

        return $e;
    }

    public function findFunction($name)
    {
        return parent::findFunction(str::lower($name));
    }

    public function findType($name)
    {
        return parent::findType(str::lower($name));
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


    public function putType(TypeEntry $entry)
    {
        $this->types[str::lower($entry->fulledName)] = $entry;
    }

    public function putFunction(FunctionEntry $entry)
    {
        $this->functions[str::lower($entry->fulledName)] = $entry;
    }
}