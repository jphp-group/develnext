package org.develnext.jphp.ext.javafx.classes;

import javafx.geometry.Point2D;
import javafx.scene.Node;
import javafx.scene.control.PopupControl;
import javafx.stage.PopupWindow;
import javafx.stage.Window;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Nullable;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Name(JavaFXExtension.NS + "UXPopupWindow")
public class UXPopupWindow<T extends PopupWindow> extends UXWindow<PopupWindow> {
    interface WrappedInterface {
        @Property boolean autoFix();
        @Property boolean autoHide();
        @Property boolean hideOnEscape();

        void show(@Nullable Window owner);
        void show(@Nullable Window owner, double screenX, double screenY);

        void hide();
    }

    public UXPopupWindow(Environment env, T wrappedObject) {
        super(env, wrappedObject);
    }

    public UXPopupWindow(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Override
    @SuppressWarnings("unchecked")
    public T getWrappedObject() {
        return (T) super.getWrappedObject();
    }

    @Signature
    public void __construct() {
        __wrappedObject = new PopupControl();
    }

    @Signature
    public void showByNode(Node node) {
        showByNode(node, 0, 0);
    }

    @Signature
    public void showByNode(Node node, int offsetX, int offsetY) {
        Point2D screen = node.localToScreen(node.getLayoutX(), node.getLayoutY());

        getWrappedObject().show(node, screen.getX() + offsetX, screen.getY() + offsetY);
    }
}
