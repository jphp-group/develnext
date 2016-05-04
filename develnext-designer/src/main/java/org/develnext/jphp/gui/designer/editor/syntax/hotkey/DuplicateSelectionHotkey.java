package org.develnext.jphp.gui.designer.editor.syntax.hotkey;

import javafx.scene.input.KeyCode;
import javafx.scene.input.KeyCombination;
import javafx.scene.input.KeyEvent;
import org.develnext.jphp.gui.designer.editor.syntax.AbstractCodeArea;
import org.fxmisc.richtext.Paragraph;
import org.fxmisc.richtext.TwoDimensional;

import java.util.Collection;

public class DuplicateSelectionHotkey extends AbstractHotkey {
    @Override
    public void apply(AbstractCodeArea area, KeyEvent keyEvent) {
        if (area.getSelection().getLength() == 0) {
            int currentParagraph = area.getCurrentParagraph();

            Paragraph<?, Collection<String>> paragraph = area.getParagraph(currentParagraph);

            int caretColumn = area.getCaretColumn();

            TwoDimensional.Position position = area.position(currentParagraph, 0);
            TwoDimensional.Position newPosition = area.position(currentParagraph + 1, caretColumn);

            area.replaceText(position.toOffset(), position.toOffset(), paragraph.getText() + "\n");

            area.moveTo(newPosition.toOffset());
        } else {
            area.replaceSelection(area.getSelectedText() + area.getSelectedText());
        }
    }

    @Override
    public KeyCode getDefaultKeyCode() {
        return KeyCode.D;
    }

    @Override
    public KeyCombination.Modifier[] getDefaultKeyCombination() {
        return new KeyCombination.Modifier[] { KeyCombination.CONTROL_DOWN };
    }
}
