package org.develnext.lexer.jphp.classes.token;

import org.develnext.jphp.core.tokenizer.TokenMeta;
import org.develnext.jphp.core.tokenizer.token.Token;
import org.develnext.jphp.core.tokenizer.token.expr.value.CallExprToken;
import org.develnext.jphp.core.tokenizer.token.stmt.ExprStmtToken;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

import java.util.List;

@Reflection.Name("CallExprToken")
@Reflection.Namespace(DevelNextLexerExtension.NS + "\\token")
public class PCallExprToken extends PSimpleToken<CallExprToken> {
    interface WrappedInterface {
        Token getName();
        List<ExprStmtToken> getParameters();
    }

    public PCallExprToken(Environment env, CallExprToken wrappedObject) {
        super(env, wrappedObject);
    }

    public PCallExprToken(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new CallExprToken(TokenMeta.empty());
    }
}
