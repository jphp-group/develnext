package org.develnext.jphp.ext.javafx.classes.event;

import javafx.event.ActionEvent;
import javafx.event.Event;
import javafx.scene.input.InputEvent;
import javafx.scene.input.InputMethodEvent;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.Memory;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Getter;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.memory.support.MemoryOperation;
import php.runtime.reflection.ClassEntity;

@Reflection.Abstract
@Reflection.Name(JavaFXExtension.NS + "event\\UXEvent")
public class UXEvent extends BaseWrapper<Event> {
    interface WrappedInterface {
        boolean isConsumed();
        void consume();
    }

    public UXEvent(Environment env, Event wrappedObject) {
        super(env, wrappedObject);
    }

    public UXEvent(Environment env, ActionEvent wrappedObject) {
        super(env, wrappedObject);
    }

    public UXEvent(Environment env, InputEvent wrappedObject) {
        super(env, wrappedObject);
    }
    public UXEvent(Environment env, InputMethodEvent wrappedObject) {
        super(env, wrappedObject);
    }

    public UXEvent(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Getter
    protected Memory getTarget(Environment env) throws Throwable {
        Object target = getWrappedObject().getTarget();

        if (target == null) {
            return Memory.NULL;
        }

        MemoryOperation operation = MemoryOperation.get(target.getClass(), null);

        if (operation == null) {
            return Memory.NULL;
        }

        return operation.unconvert(env, env.trace(), target);
    }
}
