package org.develnext.jphp.ext.javafx.classes;

import javafx.geometry.Rectangle2D;
import javafx.scene.Scene;
import javafx.scene.layout.AnchorPane;
import javafx.stage.*;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection.*;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Name(JavaFXExtension.NS + "UXForm")
public class UXForm extends UXWindow {
    interface WrappedInterface {
        @Property String title();

        @Property double maxHeight();
        @Property double maxWidth();
        @Property double minHeight();
        @Property double minWidth();

        @Property Modality modality();

        @Property Window owner();

        @Property StageStyle style();

        @Property boolean fullScreen();
        @Property boolean iconified();
        @Property boolean resizable();

        @Property boolean alwaysOnTop();
        @Property boolean maximized();

        void show();
        void showAndWait();

        void toBack();
        void toFront();
        void close();
    }

    public UXForm(Environment env, Stage wrappedObject) {
        super(env, wrappedObject);

        if (wrappedObject.getScene() == null) {
            AnchorPane layout = new AnchorPane();
            Scene scene = new Scene(layout);

            getWrappedObject().setScene(scene);
        }
    }

    public UXForm(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Override
    public Stage getWrappedObject() {
        return (Stage) super.getWrappedObject();
    }

    @Signature
    public void __construct() {
        __wrappedObject = new Stage();

        AnchorPane layout = new AnchorPane();
        Scene scene = new Scene(layout);

        getWrappedObject().setScene(scene);
    }

    @Signature
    public void __construct(StageStyle style) {
        __wrappedObject = new Stage(style);

        AnchorPane layout = new AnchorPane();
        Scene scene = new Scene(layout);

        getWrappedObject().setScene(scene);
    }

    @Setter
    protected void setScene(@Nullable Scene scene) {
        getWrappedObject().setScene(scene);
    }

    @Setter
    protected void setOwner(@Nullable Window window) {
        getWrappedObject().initOwner(window);
    }

    @Setter
    protected void setStyle(StageStyle style) {
        getWrappedObject().initStyle(style);
    }

    @Setter
    protected void setModality(Modality modality) {
        getWrappedObject().initModality(modality);
    }

    @Signature
    public void maximize() {
        Screen screen = Screen.getPrimary();
        Rectangle2D bounds = screen.getVisualBounds();

        getWrappedObject().setX(bounds.getMinX());
        getWrappedObject().setY(bounds.getMinY());
        getWrappedObject().setWidth(bounds.getWidth());
        getWrappedObject().setHeight(bounds.getHeight());
    }
}
