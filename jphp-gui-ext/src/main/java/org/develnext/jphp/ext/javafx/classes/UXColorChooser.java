package org.develnext.jphp.ext.javafx.classes;

import com.sun.javafx.scene.control.skin.CustomColorDialog;
import javafx.stage.Window;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

@Name(JavaFXExtension.NS + "UXColorChooser")
public class UXColorChooser extends BaseWrapper<CustomColorDialog> {
    public UXColorChooser(Environment env, CustomColorDialog wrappedObject) {
        super(env, wrappedObject);
    }

    public UXColorChooser(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(Window window) {
        __wrappedObject = new CustomColorDialog(window);
    }

    @Signature
    public void show() {
        getWrappedObject().show();
    }
}
