package org.develnext.jphp.ext.javafx.classes.event;

import javafx.scene.input.KeyEvent;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Getter;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Property;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Name(JavaFXExtension.NS + "event\\UXKeyEvent")
public class UXKeyEvent extends UXEvent {
    interface WrappedInterface {
        @Property String character();
        @Property String text();

        @Property boolean shiftDown();
        @Property boolean controlDown();
        @Property boolean altDown();
        @Property boolean metaDown();
        @Property boolean shortcutDown();
    }

    public UXKeyEvent(Environment env, KeyEvent wrappedObject) {
        super(env, wrappedObject);
    }

    public UXKeyEvent(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Reflection.Signature
    public void __construct(KeyEvent event, Object sender) {
        __wrappedObject = new KeyEvent(sender, null, event.getEventType(), event.getCharacter(), event.getText(), event.getCode(), event.isShiftDown(), event.isControlDown(), event.isAltDown(), event.isMetaDown());
    }

    @Override
    public KeyEvent getWrappedObject() {
        return (KeyEvent) super.getWrappedObject();
    }

    @Getter
    public String getCodeName() {
        return getWrappedObject().getCode().getName();
    }
}
