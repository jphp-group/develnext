package org.develnext.jphp.gui.designer.editor.syntax;

public class CodeAreaGutterNote {
    protected String hint;
    protected String styleClass;

    public CodeAreaGutterNote(String styleClass, String hint) {
        this.styleClass = styleClass;
        this.hint = hint;
    }

    public String getHint() {
        return hint;
    }

    public String getStyleClass() {
        return styleClass;
    }
}
