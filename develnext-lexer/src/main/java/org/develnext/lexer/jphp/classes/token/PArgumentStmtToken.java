package org.develnext.lexer.jphp.classes.token;

import org.develnext.jphp.core.tokenizer.TokenMeta;
import org.develnext.jphp.core.tokenizer.token.expr.value.NameToken;
import org.develnext.jphp.core.tokenizer.token.stmt.ArgumentStmtToken;
import org.develnext.jphp.core.tokenizer.token.stmt.ExprStmtToken;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.common.HintType;
import php.runtime.common.Modifier;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

import java.util.List;

@Reflection.Name("ArgumentStmtToken")
@Reflection.Namespace(DevelNextLexerExtension.NS + "\\token")
public class PArgumentStmtToken extends PSimpleToken<ArgumentStmtToken> {
    interface WrappedInterface {
        ExprStmtToken getValue();
        HintType getHintType();
        NameToken getHintTypeClass();
        boolean isOptional();

        boolean isReference();
        boolean isVariadic();
    }

    public PArgumentStmtToken(Environment env, ArgumentStmtToken wrappedObject) {
        super(env, wrappedObject);
    }

    public PArgumentStmtToken(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new ArgumentStmtToken(TokenMeta.empty());
    }

    @Signature
    public String getName() {
        return getWrappedObject().getName().getName();
    }
}
