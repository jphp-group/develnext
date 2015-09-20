package org.develnext.jphp.ext.javafx.classes;

import javafx.scene.media.Media;
import javafx.scene.media.MediaPlayer;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

@Reflection.Name(JavaFXExtension.NS + "UXMediaPlayer")
public class UXMediaPlayer extends BaseWrapper<MediaPlayer> {
    interface WrappedInterface {
        @Property double balance();
        @Property double rate();
    }

    public UXMediaPlayer(Environment env, MediaPlayer wrappedObject) {
        super(env, wrappedObject);
    }

    public UXMediaPlayer(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(Media media) {
        __wrappedObject = new MediaPlayer(media);
    }
}
