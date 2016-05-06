package org.develnext.jphp.gui.designer.editor.syntax.hotkey;

import javafx.scene.input.KeyCode;
import javafx.scene.input.KeyEvent;
import org.develnext.jphp.gui.designer.editor.syntax.AbstractCodeArea;
import org.fxmisc.richtext.Paragraph;

import java.util.Collection;

public class BackspaceHotkey extends AbstractHotkey {
    @Override
    public boolean apply(AbstractCodeArea area, KeyEvent keyEvent) {
        int pos = area.getCaretPosition();

        if (pos > 0 && area.getSelection().getLength() == 0) {
            char chLeft = area.getText().charAt(pos - 1);
            char chRight = '\0';

            if (chLeft == ' ') {
                int offset = 0;
                String text = area.getText();

                String pText = area.getParagraph(area.getCurrentParagraph()).getText().trim();

                for (int i = pos - 1; i > 0; i--) {
                    if (text.charAt(i) == ' ') {
                        offset++;

                        if (offset >= area.getTabSize() && !pText.isEmpty()) {
                            break;
                        }
                    } else {
                        break;
                    }
                }

                if (pText.isEmpty()) {
                    offset++; // add \n
                }

                area.replaceText(pos - offset, pos, "");
            } else {
                if (area.getText().length() >= pos + 1) {
                    chRight = area.getText().charAt(pos);
                }

                area.replaceText(pos - 1, pos, "");

                if (chLeft == '[' && chRight == ']'
                        || chLeft == '{' && chRight == '}'
                        || chLeft == '(' && chRight == ')'
                        || chLeft == '"' && chRight == '"'
                        || chLeft == '\'' && chRight == '\''
                        ) {
                    area.replaceText(pos - 1, pos, "");
                }
            }
        } else {
            area.replaceSelection("");
        }

        return true;
    }

    @Override
    public KeyCode getDefaultKeyCode() {
        return KeyCode.BACK_SPACE;
    }
}
