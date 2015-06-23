package org.develnext.jphp.ext.javafx.classes;

import javafx.scene.control.ListCell;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Reflection.Name(JavaFXExtension.NS + "UXListCell")
public class UXListCell extends UXCell<ListCell> {
    interface WrappedInterface {
    }

    public UXListCell(Environment env, ListCell wrappedObject) {
        super(env, wrappedObject);
    }

    public UXListCell(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new ListCell<>();
    }
}
