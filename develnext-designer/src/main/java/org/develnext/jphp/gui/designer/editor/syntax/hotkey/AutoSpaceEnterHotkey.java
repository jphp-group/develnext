package org.develnext.jphp.gui.designer.editor.syntax.hotkey;

import javafx.scene.input.KeyCode;
import javafx.scene.input.KeyEvent;
import org.develnext.jphp.gui.designer.editor.syntax.AbstractCodeArea;
import org.fxmisc.richtext.model.Paragraph;
import org.fxmisc.richtext.model.StyledText;
import php.runtime.common.StringUtils;

import java.util.Collection;

public class AutoSpaceEnterHotkey extends AbstractHotkey {
    @Override
    public boolean apply(AbstractCodeArea area, KeyEvent keyEvent) {
        int currentParagraph = area.getCurrentParagraph();
        int pos = area.getCaretPosition();

        boolean openBlock = false;

        if (pos > 0 && area.getSelection().getLength() == 0) {
            char chLeft = area.getText().charAt(pos - 1);
            char chRight = '\0';

            if (area.getText().length() >= pos + 1) {
                chRight = area.getText().charAt(pos);
            }

            if (chLeft == '{' && chRight == '}') {
                openBlock = true;
            }
        }

        Paragraph<Collection<String>, StyledText<Collection<String>>, Collection<String>> paragraph = area.getParagraph(currentParagraph);

        StringBuilder prefix = new StringBuilder("\n");
        String text = paragraph.getText().substring(0, area.getCaretColumn());

        for (int i = 0; i < text.length(); i++) {
            char c = text.charAt(i);

            if (Character.isSpaceChar(c) || (c == '*' && !text.trim().endsWith("*/"))) {
                prefix.append(c);
                continue;
            }

            break;
        }

        int offset = 0;

        if (text.trim().endsWith("/**")) {
            try {
                Paragraph<Collection<String>, StyledText<Collection<String>>, Collection<String>> nextParagraph = area.getParagraph(currentParagraph + 1);
                String nextParagraphText = nextParagraph.getText();

                if (!nextParagraphText.trim().startsWith("*")) {
                    offset = -(prefix.length() + 3);

                    prefix.append(" *").append(" " + prefix.toString()).append("/");
                } else {
                    prefix.append(" * ");
                }
            } catch (IndexOutOfBoundsException e) {
                // nop.
            }

        }

        if (openBlock) {
            String replacement = prefix.toString() + StringUtils.repeat(' ', area.getTabSize());
            area.replaceSelection(replacement + prefix.toString());
            area.moveTo(pos + replacement.length());
        } else {
            area.replaceSelection(prefix.toString());
        }

        if (offset != 0) {
            area.moveTo(area.getCaretPosition() + offset);
        }

        return true;
    }

    @Override
    public KeyCode getDefaultKeyCode() {
        return KeyCode.ENTER;
    }
}
