/*package org.develnext.jphp.gui.designer.classes.dock;

import javafx.geometry.Pos;
import javafx.scene.Node;
import javafx.scene.layout.VBox;
import org.develnext.jphp.ext.javafx.classes.layout.UXStackPane;
import org.develnext.jphp.ext.javafx.classes.layout.UXVBox;
import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import org.dockfx.DockNode;
import org.dockfx.DockPane;
import org.dockfx.DockPos;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Reflection.Namespace(GuiDesignerExtension.NS_DOCK)
public class UXDockNode extends UXVBox<DockNode> {
    interface WrappedInterface {
        @Property String title();
        @Property Node graphic();

        @Property boolean closable();
        @Property boolean floatable();
        @Property boolean floating();
        @Property boolean minimizable();
        @Property("resizable") boolean stageResizable();

        @Property boolean maximized();
        @Property boolean minimized();

        @Property("content") Node contents();

        void close();
        void focus();

        void dock(DockPane dockPane, DockPos dockPos);
        void dock(DockPane dockPane, DockPos dockPos, Node sibling);
        void undock();

        Node getLastDockSibling();
        DockPos getLastDockPos();

        boolean isClosed();
        boolean isTabbed();
        boolean isMouseResizeZone();
        boolean isDocked();
    }

    public UXDockNode(Environment env, DockNode wrappedObject) {
        super(env, wrappedObject);
    }

    public UXDockNode(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(Node content) {
        __wrappedObject = new DockNode(content);
    }

    @Signature
    public void __construct(Node content, String title) {
        __wrappedObject = new DockNode(content, title);
    }

    @Override
    public DockNode getWrappedObject() {
        return (DockNode) super.getWrappedObject();
    }

    @Signature
    public void __construct(Node content, String title, Node graphics) {
        __wrappedObject = new DockNode(content, title, graphics);
    }
}
*/