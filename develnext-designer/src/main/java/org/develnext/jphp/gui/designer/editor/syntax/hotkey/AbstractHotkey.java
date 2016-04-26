package org.develnext.jphp.gui.designer.editor.syntax.hotkey;

import javafx.scene.input.KeyCode;
import javafx.scene.input.KeyCombination;
import javafx.scene.input.KeyEvent;
import org.develnext.jphp.gui.designer.editor.syntax.AbstractCodeArea;

import java.util.UUID;

abstract public class AbstractHotkey {
    protected String uid = UUID.randomUUID().toString();

    abstract public void apply(AbstractCodeArea area, KeyEvent keyEvent);
    abstract public KeyCode getDefaultKeyCode();

    public boolean isAffectsUndoManager() {
        return true;
    }

    public KeyCombination.Modifier[] getDefaultKeyCombination() {
        return new KeyCombination.Modifier[0];
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;

        AbstractHotkey that = (AbstractHotkey) o;

        return uid.equals(that.uid);
    }

    @Override
    public int hashCode() {
        return uid.hashCode();
    }
}
