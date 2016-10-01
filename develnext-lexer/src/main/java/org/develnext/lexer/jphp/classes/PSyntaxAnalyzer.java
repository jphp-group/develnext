package org.develnext.lexer.jphp.classes;

import org.develnext.jphp.core.syntax.SyntaxAnalyzer;
import org.develnext.jphp.core.tokenizer.TokenMeta;
import org.develnext.jphp.core.tokenizer.Tokenizer;
import org.develnext.jphp.core.tokenizer.token.ColonToken;
import org.develnext.jphp.core.tokenizer.token.CommentToken;
import org.develnext.jphp.core.tokenizer.token.SemicolonToken;
import org.develnext.jphp.core.tokenizer.token.Token;
import org.develnext.jphp.core.tokenizer.token.expr.BraceExprToken;
import org.develnext.jphp.core.tokenizer.token.expr.CommaToken;
import org.develnext.jphp.core.tokenizer.token.expr.OperatorExprToken;
import org.develnext.jphp.core.tokenizer.token.expr.ValueExprToken;
import org.develnext.jphp.core.tokenizer.token.expr.operator.DynamicAccessExprToken;
import org.develnext.jphp.core.tokenizer.token.expr.operator.KeyValueExprToken;
import org.develnext.jphp.core.tokenizer.token.expr.value.*;
import org.develnext.jphp.core.tokenizer.token.stmt.*;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.common.Messages;
import php.runtime.common.StringUtils;
import php.runtime.env.Context;
import php.runtime.env.Environment;
import php.runtime.exceptions.ParseException;
import php.runtime.ext.core.classes.WrapEnvironment;
import php.runtime.invoke.Invoker;
import php.runtime.lang.BaseWrapper;
import php.runtime.lang.exception.BaseError;
import php.runtime.reflection.ClassEntity;

import java.io.IOException;
import java.util.*;

@Reflection.Name("SyntaxAnalyzer")
@Reflection.Namespace(DevelNextLexerExtension.NS)
public class PSyntaxAnalyzer extends BaseWrapper<SyntaxAnalyzer> {
    public PSyntaxAnalyzer(Environment env, SyntaxAnalyzer wrappedObject) {
        super(env, wrappedObject);
    }

    public PSyntaxAnalyzer(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(WrapEnvironment env, Tokenizer tokenizer) {
        try {
            __wrappedObject = new SyntaxAnalyzer(env.getWrapEnvironment(), tokenizer);
        } catch (ParseException e) {
            env.getWrapEnvironment().error(e.getTraceInfo(), e.getType(), e.getMessage());
        }
    }

    @Signature
    public Context getContext() {
        return getWrappedObject().getContext();
    }

    @Signature
    public void reset(WrapEnvironment env, Tokenizer tokenizer) {
        getWrappedObject().reset(env.getWrapEnvironment(), tokenizer);
    }

    @Signature
    public List<Token> getTree() {
        return getWrappedObject().getTree();
    }

    @Signature
    public static String getRealName(String name, Token owner, NamespaceUseStmtToken.UseType useType) {
        NamespaceStmtToken namespace = null;

        if (owner instanceof ClassStmtToken) {
            namespace = ((ClassStmtToken) owner).getNamespace();
        }

        if (owner instanceof FunctionStmtToken) {
            namespace = ((FunctionStmtToken) owner).getNamespace();
        }

        return SyntaxAnalyzer.getRealName(FulledNameToken.valueOf(StringUtils.split(name, "\\")), namespace, useType).getName();
    }

    @Signature
    public Collection<ClassStmtToken> getClasses() {
        return new ArrayList<>(getWrappedObject().getClasses());
    }

    @Signature
    public Collection<FunctionStmtToken> getFunctions() {
        return new ArrayList<>(getWrappedObject().getFunctions());
    }

    @Signature
    public static List<Token> analyzeExpressionForDetectType(Environment env, String expression) throws IOException {
        return analyzeExpressionForDetectType(env, expression, false);
    }

    protected static List<Token> analyzeExpressionForDetectType(Environment env, String expression, boolean shortExpr) throws IOException {
        try {
            if (!shortExpr) {
                expression += ";";
            }

            Tokenizer tokenizer = new Tokenizer(new Context(expression));

            List<Token> rawTokens = tokenizer.fetchAll();
            List<Token> newRawTokens = new ArrayList<>();

            int arrBrace = 0, blockBrace = 0, simpleBrace = 0;

            Token firstToken = null;

            for (int i = rawTokens.size() - 1; i > 0; i--) {
                Token token = rawTokens.get(i);

                if (i == rawTokens.size() - 1) {
                    if (token instanceof DynamicAccessExprToken || token instanceof StaticAccessExprToken || token instanceof SemicolonToken) {
                        if (token instanceof SemicolonToken) {
                            newRawTokens.add(0, token);
                        }

                        continue;
                    }
                }

                if (token instanceof BreakStmtToken || token instanceof SemicolonToken || token instanceof NewExprToken || token instanceof ColonToken
                        || token instanceof CommentToken || token instanceof ReturnStmtToken || token instanceof CaseStmtToken
                        || token instanceof ExtendsStmtToken || token instanceof ImplementsStmtToken || token instanceof ClassStmtToken || token instanceof InterfaceStmtToken
                        || token instanceof TraitStmtToken || token instanceof AsStmtToken) {
                    break;
                }

                if (shortExpr && token instanceof CommaToken) {
                    break;
                }

                if (token instanceof OperatorExprToken) {
                    if (!(token instanceof DynamicAccessExprToken || token instanceof KeyValueExprToken)) {
                        break;
                    }
                }

                if (token instanceof BraceExprToken) {
                    BraceExprToken brace = (BraceExprToken) token;

                    switch (brace.getKind()) {
                        case ARRAY:
                            arrBrace += brace.isClosed() ? 1 : -1;
                            break;

                        case SIMPLE:
                            simpleBrace += brace.isClosed() ? 1 : -1;
                            break;

                        case BLOCK:
                            blockBrace += brace.isClosed() ? 1 : -1;
                            break;
                    }

                    if (arrBrace < 0 || blockBrace < 0 || simpleBrace < 0) {
                        break;
                    }
                }

                newRawTokens.add(0, token);
            }

            if (newRawTokens.size() > 0 && newRawTokens.get(0) instanceof NameToken) {
                if (newRawTokens.size() == 2) {
                    newRawTokens.remove(newRawTokens.size() - 1);
                    return newRawTokens;
                } else if (newRawTokens.size() > 2 && newRawTokens.get(1) instanceof StaticAccessExprToken) {
                    if (newRawTokens.size() == 3) {
                        newRawTokens.remove(newRawTokens.size() - 1);
                        return newRawTokens;
                    }
                }
            }

            SyntaxAnalyzer analyzer = new SyntaxAnalyzer(env, new Tokenizer(tokenizer.getContext()) {
                int i = -1;

                @Override
                public Token nextToken() {
                    i++;
                    if (i >= newRawTokens.size()) {
                        return null;
                    }

                    return newRawTokens.get(i);
                }

                @Override
                public void reset() {
                    i = -1;
                }

                @Override
                public List<Token> fetchAll() {
                    return newRawTokens;
                }
            });

            List<Token> tree = analyzer.getTree();

            Token token = tree.isEmpty() ? null : tree.get(tree.size() - 1);

            if (token instanceof ExprStmtToken) {
                List<Token> tokens = ((ExprStmtToken) token).getTokens();
                Collections.reverse(tokens);

                List<Token> expr = new ArrayList<>();

                for (Token t : tokens) {
                    if (t instanceof BreakStmtToken) continue;

                    if (t instanceof ValueExprToken || t instanceof DynamicAccessExprToken) {
                        expr.add(t);
                        continue;
                    }

                    break;
                }

                Collections.reverse(expr);

                return expr;
            }

            return Collections.emptyList();
        } catch (BaseError | ParseException e) {
            if (!shortExpr) {
                return analyzeExpressionForDetectType(env, expression, true);
            }

            if (e.getMessage() != null && e.getMessage().equals(Messages.ERR_PARSE_UNEXPECTED_END_OF_STRING.fetch())) {
                return Arrays.asList(new StringExprToken(TokenMeta.of(""), StringExprToken.Quote.DOUBLE));
            }

            return Collections.emptyList();
        }
    }
}
