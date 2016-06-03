package org.develnext.lexer.jphp.classes;

import org.develnext.jphp.core.tokenizer.Tokenizer;
import org.develnext.jphp.core.tokenizer.token.Token;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Context;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

import java.io.IOException;
import java.util.List;

@Name("Tokenizer")
@Namespace(DevelNextLexerExtension.NS)
public class PTokenizer extends BaseWrapper<Tokenizer> {
    public PTokenizer(Environment env, Tokenizer wrappedObject) {
        super(env, wrappedObject);
    }

    public PTokenizer(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(Context context) throws IOException {
        __wrappedObject = new Tokenizer(context);
    }

    @Signature
    public Context getContext() {
        return getWrappedObject().getContext();
    }

    @Signature
    public void reset() {
        getWrappedObject().reset();
    }

    @Signature
    public Token nextToken() {
        return getWrappedObject().nextToken();
    }

    @Signature
    public List<Token> fetchAll() {
        return getWrappedObject().fetchAll();
    }
}
