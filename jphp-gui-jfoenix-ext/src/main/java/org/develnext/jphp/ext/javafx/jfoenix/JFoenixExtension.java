package org.develnext.jphp.ext.javafx.jfoenix;

import com.jfoenix.controls.JFXButton;
import com.jfoenix.controls.JFXCheckBox;
import org.develnext.jphp.ext.javafx.jfoenix.classes.UXMaterialButton;
import org.develnext.jphp.ext.javafx.jfoenix.classes.UXMaterialCheckbox;
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
    }
}
