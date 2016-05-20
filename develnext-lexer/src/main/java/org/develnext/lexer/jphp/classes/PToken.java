package org.develnext.lexer.jphp.classes;

import org.antlr.v4.runtime.CommonToken;
import org.antlr.v4.runtime.Token;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.*;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

@Name("Token")
@Namespace(DevelNextLexerExtension.NS)
public class PToken<T extends Token> extends BaseWrapper<Token> {
    interface WrappedInterface {
        @Property int type();
        @Property String text();

        @Property int line();
        @Property("position") int charPositionInLine();

        @Property int channel();

        @Property int startIndex();
        @Property int stopIndex();

        @Property int tokenIndex();
    }

    public PToken(Environment env, T wrappedObject) {
        super(env, wrappedObject);
    }

    public PToken(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(int type) {
        __wrappedObject = new CommonToken(type);
    }

    @Signature
    public void __construct(int type, String text) {
        __wrappedObject = new CommonToken(type, text);
    }
}
