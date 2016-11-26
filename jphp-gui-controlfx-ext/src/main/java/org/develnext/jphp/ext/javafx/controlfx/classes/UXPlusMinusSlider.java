package org.develnext.jphp.ext.javafx.controlfx.classes;

import javafx.geometry.Orientation;
import org.controlsfx.control.PlusMinusSlider;
import org.develnext.jphp.ext.javafx.classes.UXControl;
import org.develnext.jphp.ext.javafx.controlfx.ControlFXExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Property;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Reflection.Namespace(ControlFXExtension.NS)
public class UXPlusMinusSlider extends UXControl<PlusMinusSlider> {
    interface WrappedInterface {
        @Property Orientation orientation();
        @Property double value();
    }

    public UXPlusMinusSlider(Environment env, PlusMinusSlider wrappedObject) {
        super(env, wrappedObject);
    }

    public UXPlusMinusSlider(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Reflection.Signature
    public void __construct() {
        __wrappedObject = new PlusMinusSlider();
    }
}
