package org.develnext.jphp.ext.javafx.classes.layout;

import javafx.geometry.Pos;
import javafx.scene.Node;
import javafx.scene.layout.HBox;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

import java.util.List;

@Reflection.Name(JavaFXExtension.NS + "layout\\UXHBox")
public class UXHBox extends UXPane<HBox> {
    interface WrappedInterface {
        @Property double spacing();
        @Property Pos alignment();
        @Property boolean fillHeight();

        void requestLayout();
    }

    public UXHBox(Environment env, HBox wrappedObject) {
        super(env, wrappedObject);
    }

    public UXHBox(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new HBox();
    }

    @Signature
    public void __construct(List<Node> children) {
        __wrappedObject = new HBox(children.toArray(new Node[children.size()]));
    }
}
