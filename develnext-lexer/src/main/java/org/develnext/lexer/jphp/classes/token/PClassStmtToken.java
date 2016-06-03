package org.develnext.lexer.jphp.classes.token;

import org.develnext.jphp.core.tokenizer.TokenMeta;
import org.develnext.jphp.core.tokenizer.token.CommentToken;
import org.develnext.jphp.core.tokenizer.token.Token;
import org.develnext.jphp.core.tokenizer.token.expr.value.FulledNameToken;
import org.develnext.jphp.core.tokenizer.token.expr.value.NameToken;
import org.develnext.jphp.core.tokenizer.token.stmt.*;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.Memory;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.common.HintType;
import php.runtime.common.Modifier;
import php.runtime.env.Environment;
import php.runtime.memory.ArrayMemory;
import php.runtime.reflection.ClassEntity;

import java.util.ArrayList;
import java.util.List;

@Name("ClassStmtToken")
@Namespace(DevelNextLexerExtension.NS + "\\token")
public class PClassStmtToken extends PSimpleToken<ClassStmtToken> {
    interface WrappedInterface {
        ClassEntity.Type getClassType();
        NameToken getName();
        String getFulledName();

        boolean isAbstract();
        boolean isFinal();

        Modifier getModifier();

        List<ConstStmtToken> getConstants();
        List<ClassVarStmtToken> getProperties();
        List<MethodStmtToken> getMethods();
    }

    public PClassStmtToken(Environment env, ClassStmtToken wrappedObject) {
        super(env, wrappedObject);
    }

    public PClassStmtToken(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new ClassStmtToken(TokenMeta.empty());
    }

    @Signature
    public Memory __debugInfo() {
        ArrayMemory memory = new ArrayMemory();
        memory.refOfIndex("*name").assign(getWrappedObject().getFulledName());
        return memory.toConstant();
    }

    @Signature
    public String getComment() {
        CommentToken docComment = getWrappedObject().getDocComment();
        return docComment == null ? null : docComment.getComment();
    }

    @Signature
    public String getNamespaceName() {
        NamespaceStmtToken namespace = getWrappedObject().getNamespace();
        return namespace == null || namespace.getName() == null ? null : namespace.getName().getName();
    }

    @Signature
    public String getExtendName() {
        ExtendsStmtToken extend = getWrappedObject().getExtend();
        return extend == null ? null : extend.getName().getName();
    }

    @Signature
    public List<String> getImplementNames() {
        List<String> r = new ArrayList<>();

        if (getWrappedObject().getImplement() != null) {
            for (FulledNameToken token : getWrappedObject().getImplement().getNames()) {
                r.add(token.getName());
            }
        }

        return r;
    }

    @Signature
    public List<String> getUseNames() {
        List<String> r = new ArrayList<>();
        for (NameToken token : getWrappedObject().getUses()) {
            r.add(token.getName());
        }

        return r;
    }
}
