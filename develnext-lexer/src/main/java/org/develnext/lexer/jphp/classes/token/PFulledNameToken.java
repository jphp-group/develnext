package org.develnext.lexer.jphp.classes.token;

import org.develnext.jphp.core.tokenizer.token.expr.value.FulledNameToken;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Reflection.Name("FulledNameToken")
@Reflection.Namespace(DevelNextLexerExtension.NS + "\\token")
public class PFulledNameToken extends PNameToken<FulledNameToken> {
    public PFulledNameToken(Environment env, FulledNameToken wrappedObject) {
        super(env, wrappedObject);
    }

    public PFulledNameToken(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(String[] names) {
        __wrappedObject = FulledNameToken.valueOf(names);
    }
}
