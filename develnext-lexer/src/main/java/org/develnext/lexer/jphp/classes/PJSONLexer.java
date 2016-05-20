package org.develnext.lexer.jphp.classes;

import org.antlr.v4.runtime.ANTLRInputStream;
import org.develnext.lexer.css.CSSLexer;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import org.develnext.lexer.json.JSONLexer;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

import java.io.IOException;
import java.io.InputStream;

@Name("JSONLexer")
@Namespace(DevelNextLexerExtension.NS)
public class PJSONLexer extends PAbstractLexer<JSONLexer> {
    public PJSONLexer(Environment env, JSONLexer wrappedObject) {
        super(env, wrappedObject);
    }

    public PJSONLexer(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(InputStream stream) throws IOException {
        __wrappedObject = new JSONLexer(new ANTLRInputStream(this.stream = stream));
    }
}
