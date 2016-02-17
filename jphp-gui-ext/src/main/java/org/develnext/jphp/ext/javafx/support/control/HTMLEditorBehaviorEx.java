package org.develnext.jphp.ext.javafx.support.control;

import javafx.scene.web.HTMLEditor;
import java.util.ArrayList;
import java.util.List;
import com.sun.javafx.scene.control.behavior.BehaviorBase;
import com.sun.javafx.scene.control.behavior.KeyBinding;
import com.sun.javafx.scene.web.skin.HTMLEditorSkin;
import static javafx.scene.input.KeyCode.B;
import static javafx.scene.input.KeyCode.F12;
import static javafx.scene.input.KeyCode.I;
import static javafx.scene.input.KeyCode.TAB;
import static javafx.scene.input.KeyCode.U;


/**
 * HTML editor behavior.
 */
public class HTMLEditorBehaviorEx extends BehaviorBase<HTMLEditorEx> {
    protected static final List<KeyBinding> HTML_EDITOR_BINDINGS = new ArrayList<KeyBinding>();

    static {
        HTML_EDITOR_BINDINGS.add(new KeyBinding(B, "bold").shortcut());
        HTML_EDITOR_BINDINGS.add(new KeyBinding(I, "italic").shortcut());
        HTML_EDITOR_BINDINGS.add(new KeyBinding(U, "underline").shortcut());
        
        HTML_EDITOR_BINDINGS.add(new KeyBinding(F12, "F12"));
        HTML_EDITOR_BINDINGS.add(new KeyBinding(TAB, "TraverseNext").ctrl());
        HTML_EDITOR_BINDINGS.add(new KeyBinding(TAB, "TraversePrevious").ctrl().shift());
    }

    public HTMLEditorBehaviorEx(HTMLEditorEx htmlEditor) {
        super(htmlEditor, HTML_EDITOR_BINDINGS);
    }

    @Override
    protected void callAction(String name) {
        if ("bold".equals(name) || "italic".equals(name) || "underline".equals(name)) {
            HTMLEditor editor = getControl();
            HTMLEditorSkin editorSkin = (HTMLEditorSkin)editor.getSkin();
            editorSkin.keyboardShortcuts(name);
        } else {
            super.callAction(name);
        }
    }
}
