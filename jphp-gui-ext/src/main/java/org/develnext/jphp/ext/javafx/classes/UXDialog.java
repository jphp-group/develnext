package org.develnext.jphp.ext.javafx.classes;

import javafx.scene.control.Alert;
import javafx.scene.control.ButtonType;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseObject;
import php.runtime.reflection.ClassEntity;

@Name(JavaFXExtension.NS + "UXDialog")
public class UXDialog extends BaseObject {
    public UXDialog(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public static String show(String text, Alert.AlertType type) {
        Alert alert = new Alert(type);

        alert.setResizable(false);
        alert.setContentText(text);
        alert.setHeaderText(null);

        ButtonType result = alert.showAndWait().orElse(null);
        return result == null ? null : result.getButtonData().getTypeCode();
    }

    @Signature
    public static String show(String text) {
        return show(text, Alert.AlertType.INFORMATION);
    }

    @Signature
    public static boolean confirm(String question) {
        Alert alert = new Alert(Alert.AlertType.CONFIRMATION);

        alert.setResizable(false);
        alert.setContentText(question);
        alert.setHeaderText(null);

        ButtonType result = alert.showAndWait().orElse(null);

        return result != ButtonType.CANCEL;
    }
}
