package org.develnext.lexer.jphp.classes.token;

import org.develnext.jphp.core.tokenizer.TokenMeta;
import org.develnext.jphp.core.tokenizer.token.Token;
import org.develnext.jphp.core.tokenizer.token.expr.operator.DynamicAccessExprToken;
import org.develnext.jphp.core.tokenizer.token.expr.value.StaticAccessExprToken;
import org.develnext.jphp.core.tokenizer.token.stmt.ExprStmtToken;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Reflection.Name("StaticAccessExprToken")
@Reflection.Namespace(DevelNextLexerExtension.NS + "\\token")
public class PStaticAccessExprToken extends PSimpleToken<StaticAccessExprToken> {
    interface WrappedInterface {
        ExprStmtToken getFieldExpr();
    }

    public PStaticAccessExprToken(Environment env, StaticAccessExprToken wrappedObject) {
        super(env, wrappedObject);
    }

    public PStaticAccessExprToken(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new StaticAccessExprToken(TokenMeta.empty());
    }

    @Signature
    public Token getField() {
        return getWrappedObject().getField();
    }

    @Signature
    public Token getClazz() {
        return getWrappedObject().getClazz();
    }
}
