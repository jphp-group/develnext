package org.develnext.lexer.jphp.classes.token;

import org.develnext.jphp.core.tokenizer.TokenMeta;
import org.develnext.jphp.core.tokenizer.token.expr.value.VariableExprToken;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Name("VariableExprToken")
@Namespace(DevelNextLexerExtension.NS + "\\token")
public class PVariableExprToken extends PSimpleToken<VariableExprToken> {
    public PVariableExprToken(Environment env, VariableExprToken wrappedObject) {
        super(env, wrappedObject);
    }

    public PVariableExprToken(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(String name) {
        __wrappedObject = new VariableExprToken(TokenMeta.of("$" + name));
    }

    @Signature
    public String getName() {
        return getWrappedObject().getName();
    }
}
