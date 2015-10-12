package org.develnext.jphp.ext.javafx.classes;

import javafx.beans.value.ChangeListener;
import javafx.beans.value.ObservableValue;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.Memory;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.invoke.Invoker;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

@Reflection.Abstract
@Reflection.Name(JavaFXExtension.NS + "UXValue")
public class UXValue extends BaseWrapper<ObservableValue> {
    public UXValue(Environment env, ObservableValue wrappedObject) {
        super(env, wrappedObject);
    }

    public UXValue(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public Memory getValue(Environment env) {
        return Memory.wrap(env, getWrappedObject().getValue());
    }

    @Signature
    public void addListener(final Environment env, final Invoker invoker) {
        getWrappedObject().addListener(new ChangeListener() {
            @Override
            public void changed(ObservableValue observable, Object oldValue, Object newValue) {
                try {
                    invoker.callAny(oldValue, newValue);
                } catch (Throwable throwable) {
                    env.wrapThrow(throwable);
                }
            }
        });
    }
}
