package org.develnext.lexer.jphp.classes.tree;

import org.antlr.v4.runtime.tree.Tree;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Abstract;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

@Abstract
@Name("AbstractTree")
@Namespace(DevelNextLexerExtension.NS + "\\tree")
public class PAbstractTree<T extends Tree> extends BaseWrapper<Tree> {
    public PAbstractTree(Environment env, T wrappedObject) {
        super(env, wrappedObject);
    }

    public PAbstractTree(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public Tree getParent() {
        return getWrappedObject().getParent();
    }

    @Signature
    public Tree getChild(int index) {
        return getWrappedObject().getChild(index);
    }

    @Signature
    public int getChildCount() {
        return getWrappedObject().getChildCount();
    }

    @Override
    public T getWrappedObject() {
        return (T) super.getWrappedObject();
    }
}
