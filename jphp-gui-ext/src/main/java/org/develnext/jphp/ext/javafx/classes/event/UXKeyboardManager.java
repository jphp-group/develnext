package org.develnext.jphp.ext.javafx.classes.event;

import javafx.event.EventHandler;
import javafx.scene.input.KeyCombination;
import javafx.scene.input.KeyEvent;
import javafx.stage.Window;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import org.develnext.jphp.ext.javafx.support.EventProvider;
import org.develnext.jphp.ext.javafx.support.KeyboardManager;
import org.develnext.jphp.ext.javafx.support.ScriptEventHandler;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.invoke.Invoker;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

@Reflection.Name(JavaFXExtension.NS + "event\\UXKeyboardManager")
public class UXKeyboardManager extends BaseWrapper<KeyboardManager> {
    interface WrappedInterface {
        void free();
    }

    public UXKeyboardManager(Environment env, KeyboardManager wrappedObject) {
        super(env, wrappedObject);
    }

    public UXKeyboardManager(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(Window window) {
        __wrappedObject = new KeyboardManager(window);
    }

    @Signature
    public void onPress(Environment env, KeyCombination keys, Invoker handler) {
        onPress(env, keys, handler, "general");
    }

    @Signature
    public void onPress(Environment env, KeyCombination keys, Invoker handler, String group) {
        EventHandler<KeyEvent> onPress = getWrappedObject().getOnPress(keys);

        if (onPress == null) {
            onPress = new ScriptEventHandler<>(env);
            getWrappedObject().setOnPress(keys, onPress);
        }


        ScriptEventHandler eventHandler = (ScriptEventHandler) onPress;
        eventHandler.set(handler, group);
    }

    @Signature
    public void onDown(Environment env, KeyCombination keys, Invoker handler) {
        onDown(env, keys, handler, "general");
    }

    @Signature
    public void onDown(Environment env, KeyCombination keys, Invoker handler, String group) {
        EventHandler<KeyEvent> onDown = getWrappedObject().getOnDown(keys);

        if (onDown == null) {
            onDown = new ScriptEventHandler<>(env);
            getWrappedObject().setOnDown(keys, onDown);
        }


        ScriptEventHandler eventHandler = (ScriptEventHandler) onDown;
        eventHandler.set(handler, group);
    }

    @Signature
    public void onUp(Environment env, KeyCombination keys, Invoker handler) {
        onUp(env, keys, handler, "general");
    }

    @Signature
    public void onUp(Environment env, KeyCombination keys, Invoker handler, String group) {
        EventHandler<KeyEvent> onUp = getWrappedObject().getOnUp(keys);

        if (onUp == null) {
            onUp = new ScriptEventHandler<>(env);
            getWrappedObject().setOnUp(keys, onUp);
        }


        ScriptEventHandler eventHandler = (ScriptEventHandler) onUp;
        eventHandler.set(handler, group);
    }
}
