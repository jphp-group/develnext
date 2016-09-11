package org.develnext.jphp.ext.javafx.classes;

import javafx.scene.control.DatePicker;
import javafx.scene.control.TextField;
import javafx.util.StringConverter;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.Memory;
import php.runtime.annotation.Reflection.*;
import php.runtime.env.Environment;
import php.runtime.memory.StringMemory;
import php.runtime.reflection.ClassEntity;

import java.time.LocalDate;
import java.time.format.DateTimeFormatter;
import java.time.format.DateTimeParseException;

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

    @Setter
    public void setFormat(String value) {
        getWrappedObject().setConverter(new CustomConverter(value));
    }

    @Getter
    public String getFormat() {
        StringConverter<LocalDate> converter = getWrappedObject().getConverter();

        if (converter instanceof CustomConverter) {
            return ((CustomConverter) converter).format;
        }

        return null;
    }

    @Override
    protected Memory getValue(Environment env) {
        if (getWrappedObject().getConverter() == null) {
            return StringMemory.valueOf(getWrappedObject().getValue().toString());
        }

        return StringMemory.valueOf(getWrappedObject().getConverter().toString(getWrappedObject().getValue()));
    }

    @Override
    @Signature
    protected void setValue(Environment env, Memory value) {
        if (value.toString().isEmpty()) {
            getWrappedObject().setValue(null);
        } else {
            try {
                if (getWrappedObject().getConverter() != null) {
                    getWrappedObject().setValue(getWrappedObject().getConverter().fromString(value.toString()));
                } else {
                    getWrappedObject().setValue(LocalDate.parse(value.toString()));
                }
            } catch (DateTimeParseException e) {
                getWrappedObject().setValue(null);
            }
        }
    }

    static class CustomConverter extends StringConverter<LocalDate> {
        private final DateTimeFormatter dateTimeFormatter;
        protected final String format;

        CustomConverter(String format) {
            this.format = format;
            this.dateTimeFormatter = DateTimeFormatter.ofPattern(format);
        }

        @Override
        public String toString(LocalDate localDate)
        {
            if(localDate==null)
                return "";
            return dateTimeFormatter.format(localDate);
        }

        @Override
        public LocalDate fromString(String dateString)
        {
            if(dateString==null || dateString.trim().isEmpty())
            {
                return null;
            }
            return LocalDate.parse(dateString,dateTimeFormatter);
        }
    }
}
