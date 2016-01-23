package org.develnext.jphp.ext.javafx.classes;

import javafx.scene.image.Image;
import javafx.scene.image.PixelReader;
import javafx.scene.image.WritableImage;
import javafx.scene.paint.Color;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.ext.core.classes.stream.Stream;
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
    public UXImage(Environment env, WritableImage wrappedObject) {
        super(env, wrappedObject);
    }

    public UXImage(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(Environment env, InputStream is) throws IOException {
        __wrappedObject = new Image(is);

        Stream.closeStream(env, is);
    }

    @Signature
    public void __construct(Environment env, InputStream is, double requiredWidth, double requiredHeight) throws IOException {
        __construct(env, is, requiredWidth, requiredHeight, true);
    }

    @Signature
    public void __construct(Environment env, InputStream is, double requiredWidth, double requiredHeight, boolean proportional) throws IOException {
        __wrappedObject = new Image(is, requiredWidth, requiredHeight, proportional, false);

        Stream.closeStream(env, is);
    }

    @Signature
    public Color getPixelColor(int x, int y) {
        PixelReader pixelReader = getWrappedObject().getPixelReader();
        try {
            return pixelReader == null ? null : pixelReader.getColor(x, y);
        } catch (IndexOutOfBoundsException e) {
            return null;
        }
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
