package org.develnext.jphp.ext.javafx.classes.layout;

import javafx.scene.Node;
import javafx.scene.control.ScrollPane;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import org.develnext.jphp.ext.javafx.classes.UXControl;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Getter;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Setter;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Reflection.Name(JavaFXExtension.NS + "layout\\UXScrollPane")
public class UXScrollPane<T extends ScrollPane> extends UXControl<ScrollPane> {
    interface WrappedInterface {
        @Property Node content();

        @Property boolean fitToWidth();
        @Property boolean fitToHeight();

        @Property ScrollPane.ScrollBarPolicy vbarPolicy();
        @Property ScrollPane.ScrollBarPolicy hbarPolicy();
    }

    public UXScrollPane(Environment env, T wrappedObject) {
        super(env, wrappedObject);
    }

    public UXScrollPane(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new ScrollPane();
    }

    @Signature
    public void __construct(@Reflection.Nullable Node content) {
        __wrappedObject = new ScrollPane(content);
    }

    @Override
    public T getWrappedObject() {
        return (T) super.getWrappedObject();
    }

    @Getter
    public double getScrollX() {
        return getWrappedObject().getHvalue();
    }

    @Setter
    public void setScrollX(double value) {
        getWrappedObject().setHvalue(value);
    }

    @Getter
    public double getScrollY() {
        return getWrappedObject().getVvalue();
    }

    @Setter
    public void setScrollY(double value) {
        getWrappedObject().setVvalue(value);
    }
}
