package org.develnext.jphp.ext.gui.desktop;

import org.develnext.jphp.ext.gui.desktop.classes.Mouse;
import org.develnext.jphp.ext.gui.desktop.classes.MouseEx;
import org.develnext.jphp.ext.gui.desktop.classes.Robot;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.env.CompileScope;

public class GuiDesktopExtension extends JavaFXExtension {
    public static final String NS = "php\\desktop";

    @Override
    public Status getStatus() {
        return Status.EXPERIMENTAL;
    }

    @Override
    public void onRegister(CompileScope scope) {
        registerClass(scope, Mouse.class);
        registerClass(scope, MouseEx.class);

        registerWrapperClass(scope, java.awt.Robot.class, Robot.class);
    }
}
