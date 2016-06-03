package org.develnext.lexer.jphp.classes.token;

import org.develnext.jphp.core.tokenizer.TokenMeta;
import org.develnext.jphp.core.tokenizer.token.CommentToken;
import org.develnext.jphp.core.tokenizer.token.Token;
import org.develnext.jphp.core.tokenizer.token.expr.value.NameToken;
import org.develnext.jphp.core.tokenizer.token.stmt.ClassStmtToken;
import org.develnext.jphp.core.tokenizer.token.stmt.ClassVarStmtToken;
import org.develnext.jphp.core.tokenizer.token.stmt.ExprStmtToken;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.common.Modifier;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Reflection.Name("ClassVarStmtToken")
@Reflection.Namespace(DevelNextLexerExtension.NS + "\\token")
public class PClassVarStmtToken extends PSimpleToken<ClassVarStmtToken> {
    public PClassVarStmtToken(Environment env, ClassVarStmtToken wrappedObject) {
        super(env, wrappedObject);
    }

    public PClassVarStmtToken(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new ClassVarStmtToken(TokenMeta.empty());
    }

    @Signature
    public String getVariable() {
        return getWrappedObject().getVariable().getName();
    }

    @Signature
    public ExprStmtToken getValue() {
        return getWrappedObject().getValue();
    }

    @Signature
    public Modifier getModifier() {
        return getWrappedObject().getModifier();
    }

    @Signature
    public boolean isStatic() {
        return getWrappedObject().isStatic();
    }

    @Signature
    public String getComment() {
        CommentToken docComment = getWrappedObject().getDocComment();
        return docComment == null ? null : docComment.getComment();
    }
}
