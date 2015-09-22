package org.develnext.jphp.ext.javafx.classes;

import javafx.scene.control.DatePicker;
import javafx.scene.control.TextField;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.Memory;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

import java.time.LocalDate;

@Name(JavaFXExtension.NS + "UXDatePicker")
public class UXDatePicker extends UXComboBoxBase<DatePicker> {
    interface WrappedInterface {
        @Property
        TextField editor();

        @Property
        boolean showWeekNumbers();
    }

    public UXDatePicker(Environment env, DatePicker wrappedObject) {
        super(env, wrappedObject);
    }

    public UXDatePicker(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new DatePicker();
    }

    @Override
    @Signature
    protected void setValue(Environment env, Memory value) {
        if (value.toString().isEmpty()) {
            getWrappedObject().setValue(null);
        } else {
            getWrappedObject().setValue(LocalDate.parse(value.toString()));
        }
    }
}
