package org.develnext.jphp.ext.javafx.classes;

import com.sun.javafx.scene.control.behavior.TextFieldBehavior;
import com.sun.javafx.scene.control.skin.TextFieldSkin;
import javafx.geometry.Pos;
import javafx.scene.control.ContextMenu;
import javafx.scene.control.TextField;
import javafx.scene.input.MouseButton;
import javafx.scene.input.MouseEvent;
import javafx.scene.paint.Color;
import javafx.scene.paint.Paint;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Name(JavaFXExtension.NS + "UXTextField")
public class UXTextField extends UXTextInputControl {
    interface WrappedInterface {
        @Property Pos alignment();
        @Property int prefColumnCount();
    }

    public UXTextField(Environment env, TextField wrappedObject) {
        super(env, wrappedObject);
    }

    public UXTextField(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Override
    public TextField getWrappedObject() {
        return (TextField) super.getWrappedObject();
    }

    @Signature
    public void __construct() {
        __construct("");
    }

    @Signature
    public void __construct(String text) {
        __wrappedObject = new TextField(text);

        getWrappedObject().setContextMenu(new ContextMenu());

        getWrappedObject().setSkin(new TextFieldSkin(getWrappedObject(), new TextFieldBehavior(getWrappedObject()) {
            @Override
            public void mouseReleased(MouseEvent e) {
                if (e.getButton() == MouseButton.SECONDARY) {
                    return; // don't allow context menu to show default context menu
                }

                super.mouseReleased(e);
            }
        }));
    }
}
