package org.develnext.jphp.ext.javafx.classes;

import javafx.scene.image.Image;
import javafx.scene.input.ClipboardContent;
import javafx.scene.input.Dragboard;
import javafx.scene.input.TransferMode;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.*;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

import java.util.Set;

@Reflection.Name(JavaFXExtension.NS + "UXDragboard")
public class UXDragboard extends BaseWrapper<Dragboard> {
    interface WrappedInterface {
        @Property @Nullable Image dragView();

        @Property double dragViewOffsetX();
        @Property double dragViewOffsetY();

        @Property Set<TransferMode> transferModes();
    }

    public UXDragboard(Environment env, Dragboard wrappedObject) {
        super(env, wrappedObject);
    }

    public UXDragboard(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(Dragboard dragboard) {
        __wrappedObject = dragboard;
    }

    @Getter
    public String getString() {
        return getWrappedObject().hasString() ? getWrappedObject().getString() : null;
    }

    @Getter
    public Image getImage() {
        return getWrappedObject().hasImage() ? getWrappedObject().getImage() : null;
    }

    @Setter
    public void setString(String text) {
        ClipboardContent content = new ClipboardContent();
        content.putString(text);

        getWrappedObject().setContent(content);
    }

    @Setter
    public void setImage(Image image) {
        ClipboardContent content = new ClipboardContent();
        content.putImage(image);

        getWrappedObject().setContent(content);
    }
}
