package org.develnext.lexer.jphp.classes.tree;

import org.antlr.v4.runtime.RuleContext;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.annotation.Reflection.WrapInterface;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Name("RuleContext")
@Namespace(DevelNextLexerExtension.NS + "\\tree")
@WrapInterface(value = RuleContext.class, skipConflicts = true)
public class PRuleContext<T extends RuleContext> extends PAbstractTree<RuleContext> {
    public PRuleContext(Environment env, T wrappedObject) {
        super(env, wrappedObject);
    }

    public PRuleContext(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new RuleContext();
    }
}
