package org.develnext.jphp.ext.javafx.classes;

import javafx.scene.media.MediaView;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Namespace(JavaFXExtension.NS)
public class UXMediaView extends UXNode<MediaView> {
    public UXMediaView(Environment env, MediaView wrappedObject) {
        super(env, wrappedObject);
    }

    public UXMediaView(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new MediaView();
    }
}
