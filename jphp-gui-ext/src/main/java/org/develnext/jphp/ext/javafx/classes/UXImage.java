package org.develnext.jphp.ext.javafx.classes;

import javafx.scene.image.Image;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

import java.io.IOException;
import java.io.InputStream;

@Reflection.Name(JavaFXExtension.NS + "UXImage")
public class UXImage extends BaseWrapper<Image> {
    interface WrappedInterface {
        @Property double width();
        @Property double height();
        @Property double progress();

        void cancel();
    }

    public UXImage(Environment env, Image wrappedObject) {
        super(env, wrappedObject);
    }

    public UXImage(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(InputStream is) throws IOException {
        __wrappedObject = new Image(is);
    }

    @Signature
    public static Image ofUrl(String url) {
        return new Image(url);
    }
    @Signature
    public static Image ofUrl(String url, boolean background) {
        return new Image(url, background);
    }
}
