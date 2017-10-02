package org.develnext.jphp.ext.javafx.jfoenix;

import com.jfoenix.controls.*;
import org.develnext.jphp.ext.javafx.jfoenix.classes.*;
import php.runtime.env.CompileScope;
import php.runtime.ext.support.Extension;

public class JFoenixExtension extends Extension {
    public final static String NS = "php\\gui";

    @Override
    public Status getStatus() {
        return Status.EXPERIMENTAL;
    }

    @Override
    public String[] getPackageNames() {
        return new String[] { "gui", "javafx" };
    }

    @Override
    public void onRegister(CompileScope compileScope) {
        registerWrapperClass(compileScope, JFXButton.class, UXMaterialButton.class);
        registerWrapperClass(compileScope, JFXCheckBox.class, UXMaterialCheckbox.class);
        registerWrapperClass(compileScope, JFXComboBox.class, UXMaterialComboBox.class);
        registerWrapperClass(compileScope, JFXTextField.class, UXMaterialTextField.class);
        registerWrapperClass(compileScope, JFXPasswordField.class, UXMaterialPasswordField.class);
        registerWrapperClass(compileScope, JFXTextArea.class, UXMaterialTextArea.class);
        registerWrapperClass(compileScope, JFXProgressBar.class, UXMaterialProgressBar.class);
    }
}
