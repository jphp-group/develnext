package org.develnext.jphp.ext.javafx.tabs.classes;

import javafx.scene.control.TabPane;
import javafx.scene.layout.Pane;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import org.develnext.jphp.ext.javafx.classes.UXTab;
import org.develnext.jphp.ext.javafx.classes.UXTabPane;
import org.develnext.jphp.ext.javafx.classes.layout.UXPane;
import org.develnext.jphp.ext.javafx.tabs.GuiTabsExtension;
import org.develnext.jphp.ext.javafx.tabs.support.DndTabPane;
import org.develnext.jphp.ext.javafx.tabs.support.DndTabPaneFactory;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseObject;
import php.runtime.reflection.ClassEntity;

@Namespace(GuiTabsExtension.NS)
public class UXDndTabPane extends UXTabPane {

    public UXDndTabPane(Environment env, DndTabPane wrappedObject) {
        super(env, wrappedObject);
    }

    public UXDndTabPane(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = DndTabPaneFactory.createDefaultDnDPane(DndTabPaneFactory.FeedbackType.MARKER, null)
                .getChildren().get(0);
    }

    @Override
    public DndTabPane getWrappedObject() {
        return (DndTabPane) super.getWrappedObject();
    }


    @Signature
    public static void setDraggable(UXTab tab, boolean value) {
        TabPane tabPane = tab.getWrappedObject().getTabPane();

        if (tabPane instanceof DndTabPane) {
            ((DndTabPane) tabPane).setDraggableTab(tab.getWrappedObject(), value);
        }
    }

    @Signature
    public boolean isDraggable(UXTab tab) {
        TabPane tabPane = tab.getWrappedObject().getTabPane();

        if (tabPane instanceof DndTabPane) {
            return ((DndTabPane) tabPane).isDraggableTab(tab.getWrappedObject());
        } else {
            return false;
        }
    }
}
