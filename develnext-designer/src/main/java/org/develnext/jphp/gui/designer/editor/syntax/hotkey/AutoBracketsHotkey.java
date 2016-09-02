package org.develnext.jphp.gui.designer.editor.syntax.hotkey;

import javafx.scene.input.KeyCode;
import javafx.scene.input.KeyEvent;
import org.develnext.jphp.gui.designer.editor.syntax.AbstractCodeArea;

public class AutoBracketsHotkey extends AbstractHotkey {
    protected boolean isCharForQuote(char ch) {
        return !Character.isLetterOrDigit(ch);
    }

    protected boolean isInvalidCase(char ch, char nextCh) {
        boolean isNeedClosedBracket = false;

        if (ch == '(' && nextCh != ')') {
            isNeedClosedBracket = true;
        } else if (ch == '[' && nextCh != ']') {
            isNeedClosedBracket = true;
        }

        if (isNeedClosedBracket) {
            if (!(nextCh == '\0' || Character.isSpaceChar(nextCh) || nextCh == '\n' || nextCh == '\r' || nextCh == '\t')) {
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

        switch (ch) {
            case "{":
                if (keyEvent.getCode() == KeyCode.OPEN_BRACKET) {
                    addClosed = '}';
                }
                break;

            case "[":
                if (keyEvent.getCode() == KeyCode.OPEN_BRACKET) {
                    addClosed = ']';
                }
                break;

            case "(":
                if (keyEvent.getCode().isLetterKey() || keyEvent.getCode().isDigitKey()) {
                    addClosed = ')';
                }

                break;

            case "\"":
                if (keyEvent.getCode() == KeyCode.QUOTE) {
                    if (area.getText().length() >= pos + 1 && area.getText().charAt(pos) == ch.charAt(0)) {
                        area.replaceText(pos, pos + 1, "");
                    } else if (isCharForQuote(nextCh)) {
                        addClosed = '"';
                    }
                }

                break;
            case "'":
                if (keyEvent.getCode() == KeyCode.QUOTE) {
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
