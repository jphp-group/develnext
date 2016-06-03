package org.develnext.lexer.jphp.classes.token;

import org.develnext.jphp.core.tokenizer.TokenMeta;
import org.develnext.jphp.core.tokenizer.token.stmt.ClassStmtToken;
import org.develnext.jphp.core.tokenizer.token.stmt.ConstStmtToken;
import org.develnext.jphp.core.tokenizer.token.stmt.ExprStmtToken;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.Memory;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.memory.ArrayMemory;
import php.runtime.memory.ReferenceMemory;
import php.runtime.reflection.ClassEntity;

import java.util.ArrayList;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

@Name("ConstStmtToken")
@Reflection.Namespace(DevelNextLexerExtension.NS + "\\token")
public class PConstStmtToken extends PSimpleToken<ConstStmtToken> {
    public PConstStmtToken(Environment env, ConstStmtToken wrappedObject) {
        super(env, wrappedObject);
    }

    public PConstStmtToken(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new ConstStmtToken(TokenMeta.empty());
    }

    @Signature
    public Memory __debugInfo(Environment env) {
        ArrayMemory r = new ArrayMemory();

        Map<String, String> re = new LinkedHashMap<>();

        for (ConstStmtToken.Item item : getWrappedObject().items) {
            re.put(item.getFulledName(), new PExprStmtToken(env, item.value).getExprString());
        }

        r.refOfIndex("*items").assign(ArrayMemory.ofStringMap(re));
        return r.toConstant();
    }

    @Signature
    @Name("getClass")
    public ClassStmtToken getClazz() {
        return getWrappedObject().getClazz();
    }

    @Signature
    public Map<String, ExprStmtToken> getItems() {
        Map<String, ExprStmtToken> r = new LinkedHashMap<>();

        for (ConstStmtToken.Item item : getWrappedObject().items) {
            r.put(item.getFulledName(), item.value);
        };

        return r;
    }
}
