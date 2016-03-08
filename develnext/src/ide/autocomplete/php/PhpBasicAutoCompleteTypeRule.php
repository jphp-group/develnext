<?php
namespace ide\autocomplete\php;

use ide\autocomplete\AutoCompleteRegion;
use ide\autocomplete\AutoCompleteTypeRule;
use ide\Logger;
use php\gui\framework\Application;
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

    public function identifyType($string)
    {
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
                    case '{':
                    case '(':
                    case '[':
                    case '}':
                    case ')':
                    case ']':
                    case ';':
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

    public function updateStart()
    {
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

    public function updateDone()
    {

    }
}