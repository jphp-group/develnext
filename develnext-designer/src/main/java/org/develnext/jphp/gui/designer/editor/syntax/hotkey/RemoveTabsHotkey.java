package org.develnext.jphp.gui.designer.editor.syntax.hotkey;

import javafx.scene.input.KeyCode;
import javafx.scene.input.KeyCombination;
import javafx.scene.input.KeyEvent;
import org.develnext.jphp.gui.designer.editor.syntax.AbstractCodeArea;
import php.runtime.common.StringUtils;

import java.util.Scanner;

public class RemoveTabsHotkey extends AbstractHotkey {

    @Override
    public boolean apply(AbstractCodeArea area, KeyEvent keyEvent) {
        String tab = StringUtils.repeat(' ', area.getTabSize());

        if (area.getSelection().getLength() == 0) {
            area.replaceSelection(tab);
        } else {
            int pos = area.getCaretPosition() - area.getTabSize();

            int start = area.getSelection().getStart();
            int end = area.getSelection().getEnd();

            String text = area.getSelectedText();

            Scanner scanner = new Scanner(text);
            StringBuilder sb = new StringBuilder(text.length());

            int stripLines = 0;

            while (scanner.hasNextLine()) {
                String line = scanner.nextLine();

                if (line.startsWith(tab)) {
                    line = line.substring(tab.length());
                    stripLines += 1;
                }

                sb.append(line).append("\n");
            }

            CharSequence sequence = sb.toString().subSequence(0, sb.length() - 1);

            area.replaceSelection(sequence.toString());
            area.moveTo(pos);

            area.selectRange(start, end - area.getTabSize() * stripLines);
        }

        return true;
    }

    @Override
    public KeyCode getDefaultKeyCode() {
        return KeyCode.TAB;
    }

    @Override
    public KeyCombination.Modifier[] getDefaultKeyCombination() {
        return new KeyCombination.Modifier[] { KeyCombination.SHIFT_DOWN };
    }
}
