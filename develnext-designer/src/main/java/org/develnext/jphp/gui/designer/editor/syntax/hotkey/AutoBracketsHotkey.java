package org.develnext.jphp.gui.designer.editor.syntax.hotkey;

import javafx.scene.input.KeyCode;
import javafx.scene.input.KeyEvent;
import org.develnext.jphp.gui.designer.editor.syntax.AbstractCodeArea;
import org.fxmisc.richtext.model.Paragraph;

import java.util.Collection;

public class AutoBracketsHotkey extends AbstractHotkey {
    protected boolean isCharForQuote(char ch) {
        return !Character.isLetterOrDigit(ch);
    }

    protected boolean isInvalidCase(char ch, char nextCh) {
        boolean isNeedClosedBracket = false;

        if (ch == '(' && nextCh != ')' && nextCh != ']' && nextCh != '}') {
            isNeedClosedBracket = true;
        } else if (ch == '[' && nextCh != ']' && nextCh != ')' && nextCh != '}') {
            isNeedClosedBracket = true;
        }

        if (isNeedClosedBracket) {
            if (!(nextCh == '\0' || Character.isSpaceChar(nextCh)
                    || nextCh == '\n' || nextCh == '\r' || nextCh == '\t'
                    || nextCh == ';' || nextCh == ',' || nextCh == ':'
            )) {
                return true;
            }
        }

        return false;
    }

    @Override
    public boolean apply(AbstractCodeArea area, KeyEvent keyEvent) {
        char addClosed = '\0';

        int pos = area.getCaretPosition();

        if (area.getText().isEmpty()) return false;

        String ch = area.getText(pos - 1, pos);
        char nextCh = area.getText().length() >= pos + 1 ? area.getText(pos, pos + 1).charAt(0) : '\0';

        if (isInvalidCase(ch.charAt(0), nextCh)) {
            return false;
        }

        Paragraph<Collection<String>, Collection<String>> paragraph = area.getParagraph(area.getCurrentParagraph());

        int s1_count = 0;
        int s2_count = 0;
        int s3_count = 0;

        int q1_count = 0;
        int q2_count = 0;

        for (int i = 0; i < paragraph.length(); i++) {
            char c = paragraph.charAt(i);

            if (c == '(') s1_count++;
            if (c == ')') s1_count--;

            if (c == '[') s2_count++;
            if (c == ']') s2_count--;

            if (c == '{') s3_count++;
            if (c == '}') s3_count--;

            if (c == '"') q2_count++;
            if (c == '\'') q1_count++;
        }

        switch (ch) {
            case "{":
                if (keyEvent.getCode() == KeyCode.OPEN_BRACKET && s3_count != 0) {
                    addClosed = '}';
                }
                break;

            case "[":
                if (keyEvent.getCode() == KeyCode.OPEN_BRACKET && s2_count != 0) {
                    addClosed = ']';
                }
                break;

            case "(":
                if ((keyEvent.getCode().isLetterKey() || keyEvent.getCode().isDigitKey()) && s1_count != 0) {
                    addClosed = ')';
                }

                break;

            case "\"":
                if (keyEvent.getCode() == KeyCode.QUOTE && q2_count % 2 != 0) {
                    if (area.getText().length() >= pos + 1 && area.getText().charAt(pos) == ch.charAt(0)) {
                        area.replaceText(pos, pos + 1, "");
                    } else if (isCharForQuote(nextCh)) {
                        addClosed = '"';
                    }
                }

                break;
            case "'":
                if (keyEvent.getCode() == KeyCode.QUOTE && q1_count % 2 != 0) {
                    if (area.getText().length() >= pos + 1 && area.getText().charAt(pos) == ch.charAt(0)) {
                        area.replaceText(pos, pos + 1, "");
                    } else if (isCharForQuote(nextCh)) {
                        addClosed = '\'';
                    }
                }

                break;

            case "}":
            case "]":
            case ")":


                if (area.getText().length() >= pos + 1 &&
                        (keyEvent.getCode() == KeyCode.CLOSE_BRACKET || keyEvent.getCode().isLetterKey() || keyEvent.getCode().isDigitKey())) {
                    if (area.getText().charAt(pos) == ch.charAt(0)) {

                        if (ch.equals(")") && s1_count == 0) {
                            break;
                        }

                        if (ch.equals("]") && s2_count == 0) {
                            break;
                        }

                        if (ch.equals("}") && s3_count == 0) {
                            break;
                        }

                        area.replaceText(pos, pos + 1, "");
                    }
                }
        }

        if (addClosed != '\0') {
            area.replaceSelection(String.valueOf(addClosed));
            area.moveTo(pos);
            return true;
        } else {
            return false;
        }
    }

    @Override
    public KeyCode getDefaultKeyCode() {
        return null;
    }
}
