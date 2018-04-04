package org.develnext.jphp.ext.javafx.tabs;

import org.develnext.jphp.ext.javafx.tabs.classes.UXDndTabPane;
import org.develnext.jphp.ext.javafx.tabs.support.DndTabPane;
import php.runtime.env.CompileScope;
import php.runtime.ext.support.Extension;

public class GuiTabsExtension extends Extension {
    public static final String NS = "php\\gui";

    @Override
    public Status getStatus() {
        return Status.EXPERIMENTAL;
    }

    @Override
    public void onRegister(CompileScope scope) {
        registerWrapperClass(scope, DndTabPane.class, UXDndTabPane.class);
    }
}
