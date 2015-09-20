package org.develnext.jphp.ext.javafx.classes;

import javafx.scene.media.Media;
import javafx.util.Duration;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

@Reflection.Name(JavaFXExtension.NS + "UXMedia")
public class UXMedia extends BaseWrapper<Media> {
    interface WrappedInterface {
        @Property
        Duration duration();

        @Property
        int width();

        @Property
        int height();

        @Property
        String source();
    }

    public UXMedia(Environment env, Media wrappedObject) {
        super(env, wrappedObject);
    }

    public UXMedia(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(String source) {
        __wrappedObject = new Media(source);
    }
}
