package org.develnext.jphp.ext.javafx.support.control;

import com.sun.javafx.scene.web.skin.HTMLEditorSkin;
import javafx.css.StyleableProperty;
import javafx.scene.control.Skin;
import javafx.scene.web.HTMLEditor;

public class HTMLEditorEx extends HTMLEditor {
    public HTMLEditorEx() {
        setSkin(createDefaultSkin());
        getStyleClass().add("html-editor");
    }

    @Override protected Skin<?> createDefaultSkin() {
        return new HTMLEditorSkinEx(this);
    }
}
