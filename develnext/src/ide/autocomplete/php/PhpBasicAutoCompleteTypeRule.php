<?php
namespace ide\autocomplete\php;

use develnext\lexer\Context;
use develnext\lexer\inspector\entry\FunctionEntry;
use develnext\lexer\inspector\entry\TypeEntry;
use develnext\lexer\inspector\PHPInspector;
use develnext\lexer\SyntaxAnalyzer;
use develnext\lexer\token\CallExprToken;
use develnext\lexer\token\ClassStmtToken;
use develnext\lexer\token\DynamicAccessExprToken;
use develnext\lexer\token\ExprStmtToken;
use develnext\lexer\token\FunctionStmtToken;
use develnext\lexer\token\MethodStmtToken;
use develnext\lexer\token\NameToken;
use develnext\lexer\token\SimpleToken;
use develnext\lexer\token\StaticAccessExprToken;
use develnext\lexer\token\VariableExprToken;
use develnext\lexer\Tokenizer;
use ide\autocomplete\AutoCompleteRegion;
use ide\autocomplete\AutoCompleteTypeRule;
use ide\Ide;
use ide\Logger;
use php\gui\framework\Application;
use php\gui\text\UXFont;
use php\io\MemoryStream;
use php\lang\Environment;
use php\lib\arr;
use php\lib\char;
use php\lib\Items;
use php\lib\num;
use php\lib\Str;
use php\util\Flow;
use php\util\Regex;
use php\util\Scanner;
use phpx\parser\SourceToken;
use phpx\parser\SourceTokenizer;

class PhpBasicAutoCompleteTypeRule extends AutoCompleteTypeRule
{
    protected function fetchDynamicType(FunctionEntry $method, CallExprToken $token)
    {
        if ($dynamic = $method->data['returnDynamic']) {
            $project = Ide::project();

            $dynamic = str::replace($dynamic, '$package', $project ? $project->getPackageName() : 'app');

            /** @var ExprStmtToken $param */
            foreach ($token->getParameters() as $i => $param) {
                $dynamic = str::replace($dynamic, "\${$i}", $param->getExprString());
            }

            if ($type = $this->complete->getInspector()->findType($dynamic)) {
                return $type->fulledName;
            }
        }

        return null;
    }

    protected function findReturnType(SimpleToken $token, $previousType = null, AutoCompleteRegion $region)
    {
        $inspector = $this->complete->getInspector();

        $returnType = $previousType;

        if (!is_array($returnType)) {
            $returnType = [$returnType];
        }

        foreach ($returnType as $oneType) {
            switch ($token->getTypeName()) {
                case 'VariableExpr':
                    if ($token instanceof VariableExprToken) {
                        $variables = $region->getValues('variable');

                        foreach ($variables as $one) {
                            if ($one['name'] == $token->getName()) {
                                return $one['type'];
                            }
                        }
                    }

                    break;

                case 'Name':
                case 'FulledName':
                    $t = $region->getLastValue('tokenOwner') ?: $region->getLastValue('token');

                    $name = $token->getName();

                    if ($token->getWord()[0] != '\\') {
                        $name = $t ? SyntaxAnalyzer::getRealName($name, $t, 'CLASS') : $name;
                    }

                    $type = $inspector->findType($name);

                    if ($type) {
                        return $type->fulledName;
                    }

                    break;

                case 'DynamicAccessExpr':
                    $field = $token->getField();

                    if ($field instanceof NameToken) {
                        $type = $oneType instanceof TypeEntry ? $oneType : $inspector->findType($oneType);

                        if ($type) {
                            $property = $inspector->findProperty($type, $field->getName());

                            if ($property) {
                                $result = [];

                                foreach ((array)$property->data['type'] as $one) {
                                    if ($one instanceof TypeEntry) {
                                        $result[] = $one;
                                    } else {
                                        if ($inspector->findType($one)) {
                                            $result[] = $one;
                                        }
                                    }
                                }

                                if ($result) {
                                    return $result;
                                }
                            }
                        }
                    }

                    break;

                case 'StaticAccessExpr':
                    if ($token instanceof StaticAccessExprToken) {
                        $clazz = $token->getClazz();
                        $field = $token->getField();

                        if ($clazz instanceof NameToken) {
                            $t = $region->getLastValue('tokenOwner') ?: $region->getLastValue('token');
                            $name = $clazz->getName();

                            if ($clazz->getWord()[0] != '\\') {
                                $name = $t ? SyntaxAnalyzer::getRealName($name, $t, 'CLASS') : $name;
                            }

                            $type = $inspector->findType($name);

                            if ($type) {
                                return $type->fulledName;
                            }

                            /*if ($type && $field instanceof NameToken) {
                                $method = $inspector->findMethod($type, $field->getName());

                                if ($method && $method->static) {
                                    return $method->data['returnType'];
                                }
                            }*/
                        }
                    }

                    break;

                case 'CallExpr':
                    $name = $token->getName();

                    if ($oneType) {
                        $type = $oneType instanceof TypeEntry ? $oneType : $inspector->findType($oneType);

                        if ($name instanceof DynamicAccessExprToken) {
                            $field = $name->getField();

                            if ($field instanceof NameToken) {
                                $method = $inspector->findMethod($type, $field->getName());

                                if ($method && !$method->static) {
                                    if ($type = $this->fetchDynamicType($method, $token)) {
                                        return $type;
                                    }

                                    return $method->data['returnType'];
                                }
                            }
                        }
                        break;
                        // method
                    } else {
                        if ($name instanceof StaticAccessExprToken) {
                            $clazz = $name->getClazz();
                            $field = $name->getField();
                            if ($clazz instanceof NameToken) {
                                $t = $region->getLastValue('tokenOwner') ?: $region->getLastValue('token');
                                $name = $clazz->getName();

                                if ($clazz->getWord()[0] != '\\') {
                                    $name = $t ? SyntaxAnalyzer::getRealName($name, $t, 'CLASS') : $name;
                                }

                                $type = $inspector->findType($name);


                                if ($type && $field instanceof NameToken) {
                                    $method = $inspector->findMethod($type, $field->getName());

                                    if ($method && $method->static) {
                                        if ($type = $this->fetchDynamicType($method, $token)) {
                                            return $type;
                                        }

                                        return $method->data['returnType'];
                                    }
                                }
                            }
                        } else if ($name instanceof NameToken) {
                            $name = $name->getName();

                            $func = $inspector->findFunction($name);

                            if ($func) {
                                if ($type = $this->fetchDynamicType($func, $token)) {
                                    return $type;
                                }

                                return $func->data['returnType'];
                            }
                        }

                        // function
                    }
                    break;

                default:
                    var_dump($token->getTypeName());
            }
        }

        return null;
    }

    public function identifyType($string, AutoCompleteRegion $region)
    {
        $tokens = SyntaxAnalyzer::analyzeExpressionForDetectType($string);

        $type = null;

        $accessType = '';

        foreach ($tokens as $i => $token) {
            if ($i == sizeof($tokens) - 1) {
                switch ($token->getTypeName()) {
                    case 'DynamicAccessExpr':
                        $accessType = 'dynamic';

                        if (sizeof($tokens) > 1) {
                            break 2;
                        } else {
                            break 1;
                        }
                    case 'StaticAccessExpr':
                        $accessType = 'static';

                        if (sizeof($tokens) > 1) {
                            break 2;
                        } else {
                            break 1;
                        }
                }
            }

            $type = $this->findReturnType($token, $type, $region);

            if ($type == null) {
                break;
            }
        }

        if ($accessType && $type) {
            if (is_array($type)) {
                $r = [];

                foreach ($type as $one) {
                    if ($one instanceof TypeEntry) {
                        $r[] = $one;
                    } else {
                        $r[] = "~$accessType $one";
                    }
                }

                return $r;
            } else {
                return "~$accessType $type";
            }
        }

        if (sizeof($tokens) == 1) {
            if ($tokens[0] instanceof NameToken) {
                if (str::length($tokens[0]->getName()) >= 1) {
                    $t = $region->getLastValue('tokenOwner') ?: $region->getLastValue('token');
                    $name = $tokens[0]->getName();

                    $str = "~any " . $name . " " . ($t ? SyntaxAnalyzer::getRealName($name, $t, 'CLASS') : $name);

                    return $str;
                }
            } elseif ($tokens[0] instanceof VariableExprToken) {
                return "~variable";
            }
        }

        $string = str::trim($string);

        $ch = $string[str::length($string) - 1];

        if ($string[0] == '"' || $string[0] == "'" || $string[0] == '`' || $ch == '"' || $ch == "'" || $ch == '`') {
            return null;
        }

        if (!char::isLetterOrDigit($string[str::length($string) - 1])) {
            return null;
        }

        if ($tokens) {
            return null;
        }

        return '~any';
    }

    protected $classExists;

    protected function addFunctionRegion(FunctionStmtToken $token, ClassStmtToken $owner = null, $code = [])
    {
        $region = new AutoCompleteRegion($token->getStartLine(), $token->getStartPosition());
        $region->setToLine($token->getEndLine());
        $region->setToPos($token->getEndPosition());

        $this->complete->addRegion($region);

        $region->setValue($token, 'token');
        $region->setValue($owner, 'tokenOwner');
        $region->setValue($owner ? $owner->getFulledName() : null, 'self');

        $vars = [];

        if ($token instanceof MethodStmtToken) {
            if ($owner != null) {
                $vars['this'] = $owner->getFulledName();

                $region->setValue([
                    'name' => 'this',
                    'type' => $owner->getFulledName(),
                ], 'variable');
            } else {
                Logger::warn("Cannot find class for method when add function region, '{$token->getShortName()}' method");
            }
        }

        foreach ($token->getArguments() as $arg) {
            $type = $arg->getHintType() ? str::lower($arg->getHintType()) : ($arg->getHintTypeClass() ? $arg->getHintTypeClass()->getName() : 'mixed');
            $vars[$arg->getName()] = $type;

            $this->complete->setValueOfRegion([
                'name' => $arg->getName(),
                'type' => $type,
            ], 'variable', $token->getStartLine(), $token->getStartPosition() + 1);
        }


        $codeText = str::join($code, "\r\n");
        $regexOfVars = Regex::of('\\$([a-z\\_][a-z0-9\\_]{0,})[ ]+\\=[ ]+(.+?)(\\;|(\\)[\\r\\n\\t ]+\\{))', Regex::CASE_INSENSITIVE | Regex::MULTILINE | Regex::DOTALL);

        $r = $regexOfVars->with($codeText);


        while ($r->find()) {
            $varName = $r->group(1);

            if (!$vars[$varName] || $vars[$varName] == 'mixed') {
                $expression = $r->group(2);

                $types = $this->complete->identifyType("; " . $expression . "->", $region);

                foreach ($types as $type) {
                    if (str::startsWith($type, "~dynamic ")) {
                        $vars[$varName] = str::sub($type, 9);

                        $region->setValue([
                            'name' => $varName,
                            'type' => str::sub($type, 9)
                        ], 'variable');

                        break;
                    }
                }
            }
        }

        foreach ($code as $one) {
            if (str::trim($one) && str::contains($one, '$')) {
                $regexOfComment = Regex::of('\\/\\*\\*[ ]+\\@var[ ]+([a-z0-9_\\\\]+)[ ]+\\$([a-z0-9_]+)', Regex::CASE_INSENSITIVE | Regex::DOTALL);
                $r = $regexOfComment->with($one);

                while ($r->find()) {
                    $varName = $r->group(2);
                    $type = $r->group(1);

                    if ($type[0] != '\\') {
                        $type = SyntaxAnalyzer::getRealName($type, $owner ? $owner : $token, 'CLASS');
                    }

                    if (!$vars[$varName] || $vars[$varName] == 'mixed') {
                        $vars[$varName] = $type;

                        $region->setValue([
                            'name' => $varName,
                            'type' => $type,
                        ], 'variable');
                    }
                }

                $regexOfNew = Regex::of('\$([a-z][a-z0-9\\_]{0,})[ ]+\=[ ]+new[ ]+([a-z\\_][a-z0-9\\_]{0,})', Regex::CASE_INSENSITIVE | Regex::DOTALL);
                $r = $regexOfNew->with($one);

                while ($r->find()) {
                    $varName = $r->group(1);
                    $type = $r->group(2);

                    if ($type[0] != '\\') {
                        $type = SyntaxAnalyzer::getRealName($type, $owner ? $owner : $token, 'CLASS');
                    }

                    if (!$vars[$varName] || $vars[$varName] == 'mixed') {
                        $vars[$varName] = $type;

                        $region->setValue([
                            'name' => $varName,
                            'type' => $type,
                        ], 'variable');
                    }
                }

                $regexOfVarType = Regex::of('([a-z\\_][a-z0-9]{0,})?[ ]{0,}\\$([a-z\\_][a-z0-9\\_]{0,})', Regex::CASE_INSENSITIVE | Regex::DOTALL);
                $r = $regexOfVarType->with($one);

                while ($r->find()) {
                    $varName = $r->group(2);
                    $type = $r->group(1);

                    if ($type == 'global' || $type == 'static' || $type == 'as') continue;

                    if ($type && $type[0] != '\\') {
                        $type = SyntaxAnalyzer::getRealName($type, $owner ? $owner : $token, 'CLASS');
                    }

                    if (!$vars[$varName] || $vars[$varName] == 'mixed') {
                        $vars[$varName] = $type ?: 'mixed';

                        $region->setValue([
                            'name' => $varName,
                            'type' => $type ?: 'mixed',
                        ], 'variable');
                    }
                }
            }
        }

        foreach ($token->getLocalVariables() as $var) {
            if (!$vars[$var->getName()]) {
                $region->setValue([
                    'name' => $var->getName(),
                    'type' => 'mixed',
                ], 'variable');
            }
        }
    }

    public function updateStart($sourceCode, $caretPosition = 0, $caretLine = 0, $caretOffset = 0)
    {
        $stream = new MemoryStream();
        $stream->write($sourceCode);

        if ($sourceCode) {
            $stream->seek(0);
        }

        //var_dump($this->complete->identifyType('; app()->', $this->complete->getGlobalRegion()));

        $inspector = $this->complete->getInspector();

        $result = $inspector->loadSource($stream);

        if ($result) {
            foreach ($result['classes'] as $type) {
                /** @var TypeEntry $type */

                if (Ide::project()) {
                    $type->packages[Ide::project()->getPackageName()] = Ide::project()->getPackageName();
                }

                $this->complete->setValueOfRegion($type, 'class');

                foreach ($type->methods as $one) {
                    $code = [];

                    if ($one->startLine <= $caretLine && $one->endLine >= $caretLine) {
                        $scanner = new Scanner($sourceCode);

                        $i = 0;

                        while ($scanner->hasNextLine()) {
                            if ($i >= $one->startLine && $i <= $one->endLine) {
                                $code []= $scanner->nextLine();
                            } else {
                                $scanner->nextLine();
                            }

                            $i++;
                        }
                    }

                    $this->addFunctionRegion($one->token, $type->token, $code);
                }
            }

            foreach ($result['functions'] as $func) {
                /** @var FunctionEntry $func */
                $this->complete->setValueOfRegion($func, 'function');
                $this->addFunctionRegion($func->token);
            }
        } else {
            return false;
        }
    }

    public function update(SourceTokenizer $tokenizer, SourceToken $token, SourceToken $previousToken = null)
    {
    }

    public
    function updateDone($sourceCode)
    {

    }
}