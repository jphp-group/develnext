package org.develnext.jphp.gui.designer.classes.dock;

import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.layout.HBox;
import org.develnext.jphp.ext.javafx.classes.UXNode;
import org.develnext.jphp.ext.javafx.classes.layout.UXHBox;
import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import org.dockfx.DockTitleBar;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Reflection.Namespace(GuiDesignerExtension.NS_DOCK)
public class UXDockTitleBar extends UXHBox<DockTitleBar> {
    interface WrappedInterface {
        @Property Label label();

        @Property Button closeButton();
        @Property Button minimizeButton();
        @Property Button stateButton();

        boolean isDragging();
    }

    public UXDockTitleBar(Environment env, DockTitleBar wrappedObject) {
        super(env, wrappedObject);
    }

    public UXDockTitleBar(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(UXDockNode node) {
        __wrappedObject = new DockTitleBar(node.getWrappedObject());
    }
}
