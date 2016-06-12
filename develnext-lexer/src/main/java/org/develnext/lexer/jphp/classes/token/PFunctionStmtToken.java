package org.develnext.lexer.jphp.classes.token;

import org.develnext.jphp.core.syntax.ExpressionInfo;
import org.develnext.jphp.core.tokenizer.TokenMeta;
import org.develnext.jphp.core.tokenizer.token.CommentToken;
import org.develnext.jphp.core.tokenizer.token.Token;
import org.develnext.jphp.core.tokenizer.token.expr.value.NameToken;
import org.develnext.jphp.core.tokenizer.token.expr.value.VariableExprToken;
import org.develnext.jphp.core.tokenizer.token.stmt.ArgumentStmtToken;
import org.develnext.jphp.core.tokenizer.token.stmt.FunctionStmtToken;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.common.Modifier;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

import java.util.List;
import java.util.Map;
import java.util.Set;

@Reflection.Name("FunctionStmtToken")
@Reflection.Namespace(DevelNextLexerExtension.NS + "\\token")
public class PFunctionStmtToken<T extends FunctionStmtToken> extends PSimpleToken<T> {
    interface WrappedInterface {
        String getFulledName();
        Modifier getModifier();
    }

    public PFunctionStmtToken(Environment env, T wrappedObject) {
        super(env, wrappedObject);
    }

    public PFunctionStmtToken(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new FunctionStmtToken(TokenMeta.empty());
    }

    @Signature
    public String getComment() {
        CommentToken docComment = getWrappedObject().getDocComment();
        return docComment == null ? null : docComment.getComment();
    }

    @Signature
    public String getShortName() {
        return getWrappedObject().getName().getName();
    }

    @Signature
    public List<ArgumentStmtToken> getArguments() {
        return getWrappedObject().getArguments();
    }

    @Signature
    public Set<VariableExprToken> getLocalVariables() {
        return getWrappedObject().getLocal();
    }

    @Signature
    public Set<VariableExprToken> getStaticLocalVariables() {
        return getWrappedObject().getStaticLocal();
    }

    @Signature
    public ExpressionInfo getTypeInfo(Token token) {
        return getWrappedObject().getTypeInfo().get(token);
    }
}
