<?php
namespace ide\autocomplete\php;

use develnext\lexer\inspector\entry\TypeEntry;
use develnext\lexer\SyntaxAnalyzer;
use develnext\lexer\token\DynamicAccessExprToken;
use develnext\lexer\token\NameToken;
use develnext\lexer\token\SimpleToken;
use develnext\lexer\token\StaticAccessExprToken;
use ide\autocomplete\AutoCompleteRegion;
use ide\autocomplete\AutoCompleteTypeRule;
use ide\Logger;
use php\gui\framework\Application;
use php\io\MemoryStream;
use php\lib\arr;
use php\lib\Items;
use php\lib\num;
use php\lib\Str;
use php\util\Flow;
use phpx\parser\SourceToken;
use phpx\parser\SourceTokenizer;

class PhpBasicAutoCompleteTypeRule extends AutoCompleteTypeRule
{
    protected function tryGetVariable(SourceToken $last, array &$tokens)
    {
        switch ($last->type) {
            case 'DollarExpr':
            case 'VariableExpr':
                return '~variable';
        }

        return null;
    }

    protected function tryGetDynamicAccess(SourceToken $last, array &$tokens)
    {
        $string = '';

        foreach ($tokens as $token) {
            $string .= $token->word;
        }

        $dynamic = $last->type == 'DynamicAccessExpr';

        if (!$dynamic) {
            if ($last = arr::peak($tokens)) {
                $dynamic = $last->type == 'DynamicAccessExpr';

                if ($dynamic) {
                    arr::pop($tokens);
                }
            }
        }

        if ($dynamic) {
            $last = arr::peak($tokens);

            if ($last) {
                arr::pop($tokens);

                if ($last->word == '$this') {
                    return '~this';
                }

                if ($last->word == '$event') {
                    return '~event';
                }

                if ($last->type == 'Name') {
                    $name = $last->word;

                    if ($last = arr::peak($tokens)) {
                        if ($last->type == 'DynamicAccessExpr') {
                            arr::pop($tokens);

                            if ($last = Items::peak($tokens)) {
                                arr::pop($tokens);

                                if ($last->word == '$this') {
                                    return "~this $name";
                                }

                                return "~dynamic $name";
                            }
                        }
                    }
                }

                if (str::startsWith($string, 'app()')) {
                    return "~dynamic " . Application::class;
                }

                return "~dynamic";
            }
        }

        return null;
    }

    protected function tryGetStaticAccess(SourceToken $last, array &$tokens)
    {
        $static = $last->type == 'StaticAccessExpr';

        if (!$static) {
            if ($last = arr::peak($tokens)) {
                $static = $last->type == 'StaticAccessExpr';

                if ($static) {
                    arr::pop($tokens);
                }
            }
        }

        if ($static) {
            $class = arr::peak($tokens);

            if (arr::has(['Name', 'FulledName'], $class->type)) {
                return "~static " . $class->word;
            }
        }

        return null;
    }

    protected function findReturnType(SimpleToken $token, $previousType = null, AutoCompleteRegion $region)
    {
        $inspector = $this->complete->getInspector();

        $returnType = $previousType;

        switch ($token->getTypeName()) {
            case 'VariableExpr':
                if ($token->getWord() == '$this') {
                    if ($class = $region->getLastValue('self')) {
                        return ($class['namespace'] ? $class['namespace'] . "\\" : '') . $class['name'];
                    }
                }

                return null;

            case 'FulledName':
                $type = $inspector->findType($token->getName());

                if ($type) {
                    return $type->fulledName;
                }

                return null;

            case 'DynamicAccessExpr':
                $field = $token->getField();

                if ($field instanceof NameToken) {
                    $type = $inspector->findType($returnType);

                    if ($type) {
                        $property = $inspector->findProperty($type, $field->getName());

                        if ($property) {
                            foreach ((array) $property->data['type'] as $one) {
                                if ($inspector->findType($one)) {
                                    return $one;
                                }
                            }
                        }
                    }
                }

                return null;

            case 'StaticAccessExpr':
                if ($token instanceof StaticAccessExprToken) {
                    $clazz = $token->getClazz();
                    $field = $token->getField();

                    if ($class = $region->getLastValue('self')) {
                        return ($class['namespace'] ? $class['namespace'] . "\\" : '') . $class['name'];
                    }

                    var_dump($clazz->getName());

                    if ($clazz instanceof NameToken) {
                        $type = $inspector->findType($clazz->getName());

                        if ($type && $field instanceof NameToken) {
                            $method = $inspector->findMethod($type, $field->getName());

                            if ($method && $method->static) {
                                return $method->data['returnType'];
                            }
                        }
                    }
                }

                return null;

            case 'CallExpr':
                $name = $token->getName();

                if ($returnType) {
                    $type = $inspector->findType($returnType);

                    if ($name instanceof DynamicAccessExprToken) {
                        $field = $name->getField();

                        if ($field instanceof NameToken) {
                            $method = $inspector->findMethod($type, $field->getName());

                            if ($method && !$method->static) {
                                return $method->data['returnType'];
                            }
                        }
                    } else if ($name instanceof StaticAccessExprToken) {
                        $clazz = $name->getClazz();
                        $field = $name->getField();

                        if ($clazz instanceof NameToken) {
                            $type = $inspector->findType($clazz->getName());

                            if ($type && $field instanceof NameToken) {
                                $method = $inspector->findMethod($type, $field->getName());

                                if ($method && $method->static) {
                                    return $method->data['returnType'];
                                }
                            }
                        }
                    }

                    return null;
                    // method
                } else {
                    if ($name instanceof NameToken) {
                        $name = $name->getName();

                        $func = $inspector->findFunction($name);

                        if ($func) {
                            return $func->data['returnType'];
                        }
                    }

                    // function
                }
                break;
        }

        return null;
    }

    public function identifyType($string, AutoCompleteRegion $region)
    {
        $tokens = SyntaxAnalyzer::analyzeExpressionForDetectType($string);
        $type = null;

        print_r($tokens);
        $accessType = 'dynamic';

        foreach ($tokens as $i => $token) {
            if ($i == sizeof($tokens) - 1 && sizeof($tokens) > 1) {
                switch ($token->getTypeName()) {
                    case 'DynamicAccessExpr':
                        $accessType = 'dynamic';
                        break 2;
                    case 'FulledName':
                    case 'StaticAccessExpr':
                        $accessType = 'static';
                        break;
                }
            }

            $type = $this->findReturnType($token, $type, $region);

            if ($type == null) {
                break;
            }
        }

        var_dump($accessType, $type);

        if ($type) {
            return "~$accessType $type";
        }

        /** @var PhpAutoComplete $complete */
        $complete = $this->complete;

        $tokens = $complete->parsePrefixForRule($string);

        $token = Items::first($tokens);

        if (sizeof($tokens) == 1) {
            switch ($token->type) {
                case 'StringExpr':
                    return '~string';
            }
        }

        if ($tokens) {
            /** @var SourceToken $last */
            $last = Items::pop($tokens);

            if ($last) {
                switch ($last->word) {
                    case '!':
                    case '&':
                    case '|':

                    case '{':
                    case '(':
                    case '[':
                    case '}':
                    case ')':
                    case ']':
                    case ';':
                    case '\\':
                    case '*':
                    case '/':
                    case '.':
                    case '+':
                    case '-':
                    case '>':
                    case '<':
                    case '%':
                    case '@':
                    case '?':
                    case '=':
                        return null;
                }

                if ($ret = $this->tryGetVariable($last, $tokens)) {
                    return $ret;
                }

                if ($ret = $this->tryGetDynamicAccess($last, $tokens)) {
                    return $ret;
                }

                if ($ret = $this->tryGetStaticAccess($last, $tokens)) {
                    return $ret;
                }
            }

            if (sizeof($tokens) > 1) {
                return null;
            }
        } else {
            return null;
        }

        return '~any';
    }

    protected $classExists;

    public function updateStart($sourceCode)
    {
        $stream = new MemoryStream();
        $stream->write($sourceCode);

        if ($sourceCode) {
            $stream->seek(0);
        }

        $this->complete->getInspector()->loadSource($stream);

        $this->classExists = false;
    }

    public function update(SourceTokenizer $tokenizer, SourceToken $token, SourceToken $previousToken = null)
    {
        switch ($token->type) {
            case 'NamespaceStmt':
                if ($tk = $tokenizer->next()) {
                    if ($tk->isNamedToken()) {
                        $this->complete->setValueOfRegion($tk->word, 'namespace');
                    }
                }
                break;

            case 'NamespaceUseStmt':
                if ($tk = $tokenizer->next()) {
                    if ($tk->isNamedToken()) {
                        if ($this->classExists) {
                            // todo trait.
                        } else {
                            $this->complete->setValueOfRegion([
                                'name' => $tk->word,
                            ], 'use');
                        }
                    }
                }

                break;

            case 'ClassStmt':
                if ($tk = $tokenizer->next()) {
                    if ($tk->isNamedToken()) {
                        $this->classExists = true;

                        $this->complete->setValueOfRegion([
                            'name' => $tk->word,
                            'namespace' => $this->complete->getGlobalRegion()->getLastValue('namespace')
                        ], 'class');
                    }
                }

                break;

            case 'ConstStmt':
                if ($tk = $tokenizer->next()) {
                    if ($tk->isNamedToken()) {
                        $class =& $this->complete->getGlobalRegion()->getLastValue('class');

                        if ($class) {
                            $class['constants'][] = [
                                'name' => $tk->word,
                                'type' => 'mixed'
                            ];
                        } else {
                            $this->complete->setValueOfRegion(['name' => $tk->word, 'type' => 'mixed'], 'constant');
                        }
                    }
                }
                break;
            case 'FunctionStmt':
            case 'MethodStmt':
                if ($previousToken) {
                    if ($region = $this->complete->findRegion($previousToken->line, $previousToken->position)) {
                        $region->setToLine($token->line);
                        $region->setToPos($token->position);
                    }
                }

                $region = new AutoCompleteRegion($token->line, $token->position);

                $this->complete->addRegion($region);

                $class =& $this->complete->getGlobalRegion()->getLastValue('class');

                if ($class) {
                    $region->setValueAsRef($class, 'self');

                    $region->setValue([
                        'name' => 'this',
                        'type' => $class['name'],
                    ], 'variable');

                    if ($tk = $tokenizer->next()) {
                        if ($tk->type == 'Name') {
                            $class['methods'][$tk->word] = [
                                'name' => $tk->word,
                            ];
                        }
                    }
                }

                break;

            case 'VariableExpr':
                $region = $this->complete->findRegion($token->line, $token->position);

                $class =& $region->getLastValue('class');

                if ($class) {
                    if ($previousToken) {
                        $name = Str::sub($token->word, 1);

                        switch ($previousToken->type) {
                            case 'VarStmt':
                            case 'PublicStmt':
                            case 'ProtectedStmt':
                            case 'PrivateStmt':
                                $class['variables'][$name] = [
                                    'name' => $name,
                                    'type' => 'mixed'
                                ];
                                break;
                        }
                    }
                } else {
                    $this->complete->setValueOfRegion([
                        'name' => Str::sub($token->word, 1),
                        'type' => $previousToken && $previousToken->isNamedToken() ? $previousToken->word : 'mixed'
                    ], 'variable', $token->line, $token->position);
                }

                break;
        }
    }

    public function updateDone($sourceCode)
    {

    }
}