package org.develnext.lexer.jphp.classes.token;

import org.develnext.jphp.core.syntax.ExpressionInfo;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.annotation.Reflection;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

import java.util.Set;

@Reflection.Name("ExpressionInfo")
@Reflection.Namespace(DevelNextLexerExtension.NS + "\\token")
public class PExpressionInfo extends BaseWrapper<ExpressionInfo> {
    interface WrappedInterface {
        Set<String> getTypes();
    }

    public PExpressionInfo(Environment env, ExpressionInfo wrappedObject) {
        super(env, wrappedObject);
    }

    public PExpressionInfo(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }
}
