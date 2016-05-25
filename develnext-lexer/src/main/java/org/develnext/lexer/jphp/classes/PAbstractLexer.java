package org.develnext.lexer.jphp.classes;

import org.antlr.v4.runtime.Lexer;
import org.antlr.v4.runtime.Token;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.*;
import php.runtime.env.Environment;
import php.runtime.ext.core.classes.stream.Stream;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

import java.io.InputStream;

@Abstract
@Name("AbstractLexer")
@Namespace(DevelNextLexerExtension.NS)
public class PAbstractLexer<T extends Lexer> extends BaseWrapper<T> {
    interface WrappedInterface {
        @Property String sourceName();
        @Property String grammarFileName();
        @Property String[] modeNames();

        @Property String text();

        @Property int type();
        @Property int line();
        @Property("position") int charPositionInLine();
        @Property int state();
        @Property int channel();
        @Property int charIndex();

        @Property Token token();

        void reset();
        void skip();
        void more();

        Token emitEOF();
        Token emit();
        void emit(Token token);
        Token nextToken();

        int popMode();
        void pushMode(int value);
    }

    protected InputStream stream;

    public PAbstractLexer(Environment env, T wrappedObject) {
        super(env, wrappedObject);
    }

    public PAbstractLexer(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __destruct(Environment env) {
        Stream.closeStream(env, stream);
    }

    @Override
    public T getWrappedObject() {
        return super.getWrappedObject();
    }
}
