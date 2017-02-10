/*package org.develnext.jphp.gui.designer.classes.dock;

import org.develnext.jphp.ext.javafx.classes.UXNode;
import org.develnext.jphp.ext.javafx.classes.layout.UXStackPane;
import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import org.dockfx.DockPane;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Reflection.Namespace(GuiDesignerExtension.NS_DOCK)
public class UXDockPane extends UXStackPane<DockPane> {
    interface WrappedInterface {
        @Property boolean exclusive();

        void loadPreference(String filePath);
        void storePreference(String filePath);
    }

    public UXDockPane(Environment env, DockPane wrappedObject) {
        super(env, wrappedObject);
    }

    public UXDockPane(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new DockPane();
    }
}
*/