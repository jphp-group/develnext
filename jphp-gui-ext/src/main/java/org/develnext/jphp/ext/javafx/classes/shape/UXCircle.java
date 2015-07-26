package org.develnext.jphp.ext.javafx.classes.shape;

import javafx.scene.shape.Circle;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Name(JavaFXExtension.NS + "shape\\UXCircle")
public class UXCircle extends UXShape<Circle> {
    public UXCircle(Environment env, Circle wrappedObject) {
        super(env, wrappedObject);
    }

    public UXCircle(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new Circle();
    }

    @Signature
    public void __construct(double radius) {
        __wrappedObject = new Circle(radius);
    }
}
