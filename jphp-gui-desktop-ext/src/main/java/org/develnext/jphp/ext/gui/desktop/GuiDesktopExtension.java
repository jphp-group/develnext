package org.develnext.jphp.ext.gui.desktop;

import javafx.stage.Screen;
import org.develnext.jphp.ext.gui.desktop.classes.Mouse;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.env.CompileScope;

public class GuiDesktopExtension extends JavaFXExtension {
    public static final String NS = "php\\gui\\desktop";

    @Override
    public Status getStatus() {
        return Status.EXPERIMENTAL;
    }

    @Override
    public void onRegister(CompileScope scope) {
        registerClass(scope, Mouse.class);
    }
}
