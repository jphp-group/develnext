<?php
namespace ide\autocomplete\php;

use develnext\lexer\Context;
use develnext\lexer\inspector\entry\FunctionEntry;
use develnext\lexer\inspector\entry\TypeEntry;
use develnext\lexer\SyntaxAnalyzer;
use develnext\lexer\token\ClassStmtToken;
use develnext\lexer\token\DynamicAccessExprToken;
use develnext\lexer\token\FunctionStmtToken;
use develnext\lexer\token\MethodStmtToken;
use develnext\lexer\token\NameToken;
use develnext\lexer\token\SimpleToken;
use develnext\lexer\token\StaticAccessExprToken;
use develnext\lexer\token\VariableExprToken;
use develnext\lexer\Tokenizer;
use ide\autocomplete\AutoCompleteRegion;
use ide\autocomplete\AutoCompleteTypeRule;
use ide\Logger;
use php\gui\framework\Application;
use php\io\MemoryStream;
use php\lang\Environment;
use php\lib\arr;
use php\lib\char;
use php\lib\Items;
use php\lib\num;
use php\lib\Str;
use php\util\Flow;
use phpx\parser\SourceToken;
use phpx\parser\SourceTokenizer;

class PhpBasicAutoCompleteTypeRule extends AutoCompleteTypeRule
{
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

                        break;
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
        }

        return null;
    }

    public function identifyType($string, AutoCompleteRegion $region)
    {
        $tokens = SyntaxAnalyzer::analyzeExpressionForDetectType($string);
        $type = null;

        print_r($tokens);

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

        var_dump($accessType, $type);

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

    protected function addFunctionRegion(FunctionStmtToken $token, ClassStmtToken $owner = null)
    {
        $this->complete->addRegion(new AutoCompleteRegion($token->getStartLine(), $token->getStartPosition()));

        $this->complete->setValueOfRegion($token, 'token', $token->getStartLine(), $token->getStartPosition() + 1);
        $this->complete->setValueOfRegion($owner, 'tokenOwner', $token->getStartLine(), $token->getStartPosition() + 1);

        if ($token instanceof MethodStmtToken) {
            if ($owner != null) {
                $this->complete->setValueOfRegion([
                    'name' => 'this',
                    'type' => $owner->getFulledName(),
                ], 'variable', $token->getStartLine(), $token->getStartPosition() + 1);
            } else {
                Logger::warn("Cannot find class for method when add function region, '{$token->getShortName()}' method");
            }
        }

        foreach ($token->getArguments() as $arg) {
            $this->complete->setValueOfRegion([
                'name' => $arg->getName(),
                'type' => $arg->getHintType() ?: ($arg->getHintTypeClass() ? $arg->getHintTypeClass()->getName() : 'mixed')
            ], 'variable', $token->getStartLine(), $token->getStartPosition() + 1);
        }

        foreach ($token->getLocalVariables() as $var) {
            $info = $token->getTypeInfo($var);

            $this->complete->setValueOfRegion([
                'name' => $var->getName(),
                'type' => $info ? $info->getTypes()[0] : 'mixed',
            ], 'variable', $token->getStartLine(), $token->getStartPosition() + 1);
        }
    }

    public function updateStart($sourceCode)
    {
        $stream = new MemoryStream();
        $stream->write($sourceCode);

        if ($sourceCode) {
            $stream->seek(0);
        }

        $result = $this->complete->getInspector()->loadSource($stream);

        if ($result) {
            foreach ($result['classes'] as $type) {
                /** @var TypeEntry $type */
                $this->complete->setValueOfRegion($type, 'class');

                foreach ($type->methods as $one) {
                    $this->addFunctionRegion($one->token, $type->token);
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

    public function updateDone($sourceCode)
    {

    }
}