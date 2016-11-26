package org.develnext.jphp.ext.javafx.controlfx.classes;

import javafx.geometry.Orientation;
import org.controlsfx.control.Rating;
import org.controlsfx.control.ToggleSwitch;
import org.develnext.jphp.ext.javafx.classes.UXControl;
import org.develnext.jphp.ext.javafx.classes.UXLabeled;
import org.develnext.jphp.ext.javafx.controlfx.ControlFXExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Namespace(ControlFXExtension.NS)
public class UXToggleSwitch extends UXLabeled<ToggleSwitch> {
    interface WrappedInterface {
        @Property boolean selected();
    }

    public UXToggleSwitch(Environment env, ToggleSwitch wrappedObject) {
        super(env, wrappedObject);
    }

    public UXToggleSwitch(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new ToggleSwitch();
    }

    @Signature
    public void __construct(String text) {
        __wrappedObject = new ToggleSwitch(text);
    }
}
