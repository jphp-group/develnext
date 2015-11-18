package org.develnext.jphp.gui.designer;

import org.develnext.jphp.ext.javafx.JavaFXExtension;
import org.develnext.jphp.gui.designer.classes.*;
import org.fife.ui.autocomplete.AutoCompletion;
import php.runtime.env.CompileScope;

public class GuiDesignerExtension extends JavaFXExtension {
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
        registerWrapperClass(scope, AutoCompletion.class, UXSyntaxAutoCompletion.class);
        registerWrapperClass(scope, SyntaxTextArea.class, UXSyntaxTextArea.class);

        registerClass(scope, FileSystemWatcher.WrapWatchKey.class);
        registerClass(scope, FileSystemWatcher.class);

        registerEventProvider(new UXSyntaxTextArea.EventProvider());
    }
}
