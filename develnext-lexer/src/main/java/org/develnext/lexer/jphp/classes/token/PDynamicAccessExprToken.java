package org.develnext.lexer.jphp.classes.token;

import org.develnext.jphp.core.tokenizer.TokenMeta;
import org.develnext.jphp.core.tokenizer.token.Token;
import org.develnext.jphp.core.tokenizer.token.expr.operator.DynamicAccessExprToken;
import org.develnext.jphp.core.tokenizer.token.expr.value.CallExprToken;
import org.develnext.jphp.core.tokenizer.token.stmt.ExprStmtToken;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Reflection.Name("DynamicAccessExprToken")
@Reflection.Namespace(DevelNextLexerExtension.NS + "\\token")
public class PDynamicAccessExprToken extends PSimpleToken<DynamicAccessExprToken> {
    interface WrappedInterface {
        ExprStmtToken getFieldExpr();
    }

    public PDynamicAccessExprToken(Environment env, DynamicAccessExprToken wrappedObject) {
        super(env, wrappedObject);
    }

    public PDynamicAccessExprToken(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new DynamicAccessExprToken(TokenMeta.empty());
    }

    @Signature
    public Token getField() {
        return getWrappedObject().getField();
    }
}
