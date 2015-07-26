package org.develnext.jphp.gui.designer;

import org.develnext.jphp.ext.javafx.JavaFXExtension;
import org.develnext.jphp.gui.designer.classes.*;
import php.runtime.env.CompileScope;
import php.runtime.ext.support.Extension;

import java.awt.*;

public class GuiDesignerExtension extends Extension {
    public static final String NS = JavaFXExtension.NS + "designer";

    @Override
    public Status getStatus() {
        return Status.EXPERIMENTAL;
    }

    @Override
    public void onRegister(CompileScope scope) {
        registerClass(scope, UXDesigner.class);
        registerClass(scope, UXWriter.class);
        registerClass(scope, UXDesignPane.class);
        registerClass(scope, UXDesignProperties.class);
        registerClass(scope, UXDesignPropertyEditor.class);
        registerWrapperClass(scope, Desktop.class, UXDesktop.class);

        registerClass(scope, FileSystemWatcher.WrapWatchKey.class);
        registerClass(scope, FileSystemWatcher.class);
    }
}
