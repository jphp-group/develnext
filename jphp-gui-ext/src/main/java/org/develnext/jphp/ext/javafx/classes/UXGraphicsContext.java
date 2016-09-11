package org.develnext.jphp.ext.javafx.classes;

import javafx.scene.canvas.GraphicsContext;
import javafx.scene.image.Image;
import javafx.scene.paint.Color;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Abstract;
import php.runtime.annotation.Reflection.Nullable;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

@Abstract
@Reflection.Name(JavaFXExtension.NS + "UXGraphicsContext")
public class UXGraphicsContext extends BaseWrapper<GraphicsContext> {
    interface WrappedInterface {
        void clearRect(double x, double y, double w, double h);
        void drawImage(Image img, double x, double y);
        void drawImage(Image img, double x, double y, double w, double h);
        void drawImage(Image img, double x, double y, double w, double h, double dx, double dy, double dw, double dh);
    }

    public UXGraphicsContext(Environment env, GraphicsContext wrappedObject) {
        super(env, wrappedObject);
    }

    public UXGraphicsContext(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void setFillColor(@Nullable Color color) {
        getWrappedObject().setFill(color);
    }
}
