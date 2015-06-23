package org.develnext.jphp.ext.javafx.classes;

import javafx.collections.ObservableList;
import javafx.scene.Parent;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection.Abstract;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Property;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Abstract
@Name(JavaFXExtension.NS + "UXParent")
public class UXParent<T extends Parent> extends UXNode<Parent> {
    interface WrappedInterface {
        void layout();
        void requestLayout();

        @Property ObservableList childrenUnmodifiable();
    }

    public UXParent(Environment env, T wrappedObject) {
        super(env, wrappedObject);
    }

    public UXParent(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Override
    @SuppressWarnings("unchecked")
    public T getWrappedObject() {
        return (T) super.getWrappedObject();
    }
}
