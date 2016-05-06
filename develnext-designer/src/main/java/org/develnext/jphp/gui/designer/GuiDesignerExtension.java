package org.develnext.jphp.gui.designer;

import org.develnext.jphp.ext.javafx.JavaFXExtension;
import org.develnext.jphp.gui.designer.classes.*;
import org.develnext.jphp.gui.designer.editor.syntax.AbstractCodeArea;
import org.develnext.jphp.gui.designer.editor.syntax.impl.CssCodeArea;
import org.develnext.jphp.gui.designer.editor.syntax.impl.FxCssCodeArea;
import org.develnext.jphp.gui.designer.editor.syntax.impl.PhpCodeArea;
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
        registerClass(scope, UXDesignPane.class);
        registerClass(scope, UXDesignProperties.class);
        registerClass(scope, UXDesignPropertyEditor.class);
        registerWrapperClass(scope, AutoCompletion.class, UXSyntaxAutoCompletion.class);
        registerWrapperClass(scope, SyntaxTextArea.class, UXSyntaxTextArea.class);

        registerWrapperClass(scope, AbstractCodeArea.class, UXAbstractCodeArea.class);
        registerWrapperClass(scope, CssCodeArea.class, UXCssCodeArea.class);
        registerWrapperClass(scope, FxCssCodeArea.class, UXFxCssCodeArea.class);
        registerWrapperClass(scope, PhpCodeArea.class, UXPhpCodeArea.class);

        registerClass(scope, FileSystemWatcher.WrapWatchKey.class);
        registerClass(scope, FileSystemWatcher.class);

        registerEventProvider(new UXSyntaxTextArea.EventProvider());
        registerEventProvider(new UXAbstractCodeArea.EventProvider());
    }
}
