package org.develnext.lexer.jphp.classes.token;

import org.develnext.jphp.core.tokenizer.token.Token;
import org.develnext.jphp.core.tokenizer.token.stmt.ExprStmtToken;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.Memory;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.memory.ArrayMemory;
import php.runtime.memory.ReferenceMemory;
import php.runtime.reflection.ClassEntity;

import java.util.List;

@Reflection.Name("ExprStmtToken")
@Reflection.Namespace(DevelNextLexerExtension.NS + "\\token")
public class PExprStmtToken extends PSimpleToken<ExprStmtToken> {
    public PExprStmtToken(Environment env, ExprStmtToken wrappedObject) {
        super(env, wrappedObject);
    }

    public PExprStmtToken(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(List<Token> tokens) {
        __wrappedObject = new ExprStmtToken(null, null, tokens);
    }

    @Signature
    public Memory __debugInfo() {
        ArrayMemory memory = new ArrayMemory();
        memory.refOfIndex("*expr").assign(getExprString());
        return memory.toConstant();
    }

    @Signature
    public String getExprString() {
        StringBuilder sb = new StringBuilder();

        for (Token token : getWrappedObject().getTokens()) {
            sb.append(token.getWord()).append(" ");
        }

        return sb.toString().trim();
    }
}
