package org.develnext.lexer.jphp.classes.token;

import org.develnext.jphp.core.tokenizer.TokenMeta;
import org.develnext.jphp.core.tokenizer.token.expr.value.NameToken;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Reflection.Name("NameToken")
@Reflection.Namespace(DevelNextLexerExtension.NS + "\\token")
public class PNameToken<T extends NameToken> extends PSimpleToken<T> {
    public PNameToken(Environment env, T wrappedObject) {
        super(env, wrappedObject);
    }

    public PNameToken(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(String name) {
        __wrappedObject = NameToken.valueOf(name);
    }

    @Signature
    public String getName() {
        return getWrappedObject().getName();
    }
}
