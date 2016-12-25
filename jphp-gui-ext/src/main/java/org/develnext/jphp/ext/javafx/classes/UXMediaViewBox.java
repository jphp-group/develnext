package org.develnext.jphp.ext.javafx.classes;

import javafx.scene.media.MediaPlayer;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import org.develnext.jphp.ext.javafx.classes.layout.UXAnchorPane;
import org.develnext.jphp.ext.javafx.classes.layout.UXPanel;
import org.develnext.jphp.ext.javafx.support.control.MediaViewBox;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Nullable;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Reflection.Name(JavaFXExtension.NS + "UXMediaViewBox")
public class UXMediaViewBox extends UXPanel<MediaViewBox> {
    interface WrappedInterface {
        @Property("player") @Nullable MediaPlayer mediaPlayer();
        @Property boolean proportional();
        @Property boolean smooth();
    }

    public UXMediaViewBox(Environment env, MediaViewBox wrappedObject) {
        super(env, wrappedObject);
    }

    public UXMediaViewBox(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Override
    @Signature
    public void __construct() {
        __wrappedObject = new MediaViewBox();
    }
}
