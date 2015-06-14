package org.develnext.jphp.gui.designer;

import org.develnext.jphp.ext.javafx.JavaFXExtension;
import org.develnext.jphp.gui.designer.classes.UXDesignPane;
import org.develnext.jphp.gui.designer.classes.UXDesigner;
import org.develnext.jphp.gui.designer.classes.UXWriter;
import php.runtime.env.CompileScope;
import php.runtime.ext.support.Extension;

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
    }
}
