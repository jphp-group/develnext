package org.develnext.jphp.ext.javafx.classes.shape;

import javafx.scene.shape.Ellipse;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Name(JavaFXExtension.NS + "shape\\UXEllipse")
public class UXEllipse extends UXShape<Ellipse> {
    interface WrappedInterface {
        @Property double radiusX();
        @Property double radiusY();
    }

    public UXEllipse(Environment env, Ellipse wrappedObject) {
        super(env, wrappedObject);
    }

    public UXEllipse(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new Ellipse();
    }

    @Signature
    public void __construct(double radiusX) {
        __wrappedObject = new Ellipse(radiusX, radiusX);
    }

    @Signature
    public void __construct(double radiusX, double radiusY) {
        __wrappedObject = new Ellipse(radiusX, radiusY);
    }

    @Override
    @Signature
    protected void setWidth(double v) {
        getWrappedObject().setRadiusX(v);
    }

    @Override
    protected void setHeight(double v) {
        getWrappedObject().setRadiusY(v);
    }

    @Override
    protected void setSize(double[] size) {
        if (size.length > 1) {
            getWrappedObject().setRadiusX(size[0]);
            getWrappedObject().setRadiusY(size[1]);
        }
    }
}
