package org.develnext.jphp.ext.javafx.controlfx.classes;

import javafx.geometry.Orientation;
import org.controlsfx.control.Rating;
import org.develnext.jphp.ext.javafx.classes.UXControl;
import org.develnext.jphp.ext.javafx.controlfx.ControlFXExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Namespace(ControlFXExtension.NS)
public class UXRating extends UXControl<Rating> {
    interface WrappedInterface {
        @Property int max();
        @Property("value") int rating();
        @Property Orientation orientation();
        @Property boolean partialRating();
        @Property boolean updateOnHover();
    }

    public UXRating(Environment env, Rating wrappedObject) {
        super(env, wrappedObject);
    }

    public UXRating(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new Rating();
    }

    @Signature
    public void __construct(int max) {
        __wrappedObject = new Rating(max);
    }

    @Signature
    public void __construct(int max, int rating) {
        __wrappedObject = new Rating(max, rating);
    }
}
