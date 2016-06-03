package org.develnext.lexer.jphp.classes.token;

import org.develnext.jphp.core.tokenizer.TokenMeta;
import org.develnext.jphp.core.tokenizer.TokenType;
import org.develnext.jphp.core.tokenizer.token.Token;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.Memory;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.memory.ArrayMemory;
import php.runtime.reflection.ClassEntity;

@Reflection.Name("SimpleToken")
@Reflection.Namespace(DevelNextLexerExtension.NS + "\\token")
public class PSimpleToken<T extends Token> extends BaseWrapper<Token> {
    public PSimpleToken(Environment env, T wrappedObject) {
        super(env, wrappedObject);
    }

    public PSimpleToken(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(int type) {
        __wrappedObject = new Token(TokenMeta.empty(), TokenType.valueOf(type));
    }

    @Signature
    public Memory __debugInfo() {
        ArrayMemory memory = new ArrayMemory();
        memory.refOfIndex("*type").assign(getType());
        memory.refOfIndex("*typeName").assign(getTypeName());
        memory.refOfIndex("*word").assign(getWord());

        return memory.toConstant();
    }

    @Signature
    public int getType() {
        return getWrappedObject().getType().ordinal();
    }

    @Signature
    public String getTypeName() {
        return getWrappedObject().getClass().getSimpleName().replace("Token", "");
    }

    @Signature
    public boolean isNamedToken() {
        return getWrappedObject().isNamedToken();
    }

    @Signature
    public String getWord() {
        return getWrappedObject().getWord();
    }

    @Signature
    public int getStartLine() {
        return getWrappedObject().getMeta().getStartLine();
    }

    @Signature
    public int getEndLine() {
        return getWrappedObject().getMeta().getEndLine();
    }

    @Signature
    public int getStartPosition() {
        return getWrappedObject().getMeta().getStartPosition();
    }

    @Signature
    public int getEndPosition() {
        return getWrappedObject().getMeta().getEndPosition();
    }

    @Override
    public T getWrappedObject() {
        return (T) super.getWrappedObject();
    }
}
