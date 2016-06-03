package org.develnext.lexer.jphp.classes;

import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Context;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

import java.io.IOException;
import java.io.InputStream;
import java.nio.charset.Charset;

@Reflection.Name("Context")
@Reflection.Namespace(DevelNextLexerExtension.NS)
public class PContext extends BaseWrapper<Context> {
    public PContext(Environment env, Context wrappedObject) {
        super(env, wrappedObject);
    }

    public PContext(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(Environment env, InputStream inputStream, String moduleName, String charset) {
        __wrappedObject = new Context(inputStream, moduleName, Charset.forName(charset));
    }

    @Signature
    public void __construct(Environment env, InputStream inputStream, String moduleName) {
        __wrappedObject = new Context(inputStream, moduleName, env.getDefaultCharset());
    }

    @Signature
    public void __construct(Environment env, InputStream inputStream) {
        __wrappedObject = new Context(inputStream, null, env.getDefaultCharset());
    }

    @Signature
    public String getContent() throws IOException {
        return getWrappedObject().getContent();
    }

    @Signature
    public String getModuleName() {
        return getWrappedObject().getModuleNameNoThrow();
    }
}
