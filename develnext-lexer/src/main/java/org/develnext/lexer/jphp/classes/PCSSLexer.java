package org.develnext.lexer.jphp.classes;

import org.antlr.v4.runtime.ANTLRInputStream;
import org.develnext.lexer.css.CSSLexer;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.ext.core.classes.stream.Stream;
import php.runtime.reflection.ClassEntity;

import java.io.IOException;
import java.io.InputStream;

@Name("CSSLexer")
@Namespace(DevelNextLexerExtension.NS)
public class PCSSLexer extends PAbstractLexer<CSSLexer> {
    public PCSSLexer(Environment env, CSSLexer wrappedObject) {
        super(env, wrappedObject);
    }

    public PCSSLexer(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(InputStream stream) throws IOException {
        __wrappedObject = new CSSLexer(new ANTLRInputStream(this.stream = stream));
    }
}
