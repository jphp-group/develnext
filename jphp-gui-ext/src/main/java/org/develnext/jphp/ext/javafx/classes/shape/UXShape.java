package org.develnext.jphp.ext.javafx.classes.shape;

import javafx.scene.paint.Color;
import javafx.scene.paint.Paint;
import javafx.scene.shape.Shape;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import org.develnext.jphp.ext.javafx.classes.UXNode;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Nullable;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Name(JavaFXExtension.NS + "shape\\UXShape")
public abstract class UXShape<T extends Shape> extends UXNode<Shape> {
    interface WrappedInterface {
        @Property boolean smooth();
    }

    public UXShape(Environment env, T wrappedObject) {
        super(env, wrappedObject);
    }

    public UXShape(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Override
    public Shape getWrappedObject() {
        return (T) super.getWrappedObject();
    }

    @Signature
    public void setFillColor(@Nullable Color color) {
        getWrappedObject().setFill(color);
    }

    @Signature
    public Color getFillColor() {
        Paint fill = getWrappedObject().getFill();

        if (fill instanceof Color) {
            return (Color) fill;
        }

        return null;
    }

    @Signature
    public void setStrokeColor(@Nullable Color color) {
        getWrappedObject().setStroke(color);
    }

    @Signature
    public Color getStrokeColor() {
        Paint fill = getWrappedObject().getStroke();

        if (fill instanceof Color) {
            return (Color) fill;
        }

        return null;
    }
}
