package org.develnext.jphp.ext.javafx.support;

import javafx.beans.property.ReadOnlyProperty;
import javafx.beans.value.ObservableValue;

import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;

final public class JavaFxUtils {

    public static ObservableValue findObservable(Object object, final String property) {
        String name = property + "Property";

        Class<?> aClass = object.getClass();

        try {
            Method method = aClass.getMethod(name);

            ReadOnlyProperty bindProperty = (ReadOnlyProperty) method.invoke(object);

            return bindProperty;
        } catch (NoSuchMethodException | ClassCastException | InvocationTargetException | IllegalAccessException e) {
            throw new IllegalArgumentException("Unable to find the '" + property + "' property for watching");
        }
    }
}
