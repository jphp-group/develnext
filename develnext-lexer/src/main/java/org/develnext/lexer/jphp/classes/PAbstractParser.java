package org.develnext.lexer.jphp.classes;

import org.antlr.v4.runtime.Parser;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Abstract;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

@Abstract
@Name("AbstractParser")
@Namespace(DevelNextLexerExtension.NS)
@Reflection.WrapInterface(value = Parser.class, skipConflicts = true)
public class PAbstractParser<T extends Parser> extends BaseWrapper<Parser> {
    public PAbstractParser(Environment env, T wrappedObject) {
        super(env, wrappedObject);
    }

    public PAbstractParser(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Override
    public T getWrappedObject() {
        return (T) super.getWrappedObject();
    }
}
