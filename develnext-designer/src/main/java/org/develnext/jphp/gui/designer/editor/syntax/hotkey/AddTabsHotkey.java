package org.develnext.jphp.gui.designer.editor.syntax.hotkey;

import javafx.scene.control.IndexRange;
import javafx.scene.input.KeyCode;
import javafx.scene.input.KeyCombination;
import javafx.scene.input.KeyEvent;
import org.develnext.jphp.gui.designer.editor.syntax.AbstractCodeArea;
import org.fxmisc.richtext.Paragraph;
import org.fxmisc.richtext.TwoDimensional;
import php.runtime.common.StringUtils;

import java.util.Collection;
import java.util.Scanner;

public class AddTabsHotkey extends AbstractHotkey {
    @Override
    public boolean apply(AbstractCodeArea area, KeyEvent keyEvent) {
        if (area.getSelection().getLength() == 0) {
            area.replaceSelection(StringUtils.repeat(' ', area.getTabSize()));
        } else {
            int pos = area.getCaretPosition() + area.getTabSize();
            int start = area.getSelection().getStart();
            int end = area.getSelection().getEnd();

            String text = area.getSelectedText();

            Scanner scanner = new Scanner(text);
            StringBuilder sb = new StringBuilder(text.length());

            int lines = 0;

            while (scanner.hasNextLine()) {
                String line = scanner.nextLine();
                sb.append(StringUtils.repeat(' ', area.getTabSize())).append(line).append("\n");

                lines++;
            }

            CharSequence sequence = sb.toString().subSequence(0, sb.length() - 1);

            area.replaceSelection(sequence.toString());
            area.moveTo(pos);

            if (lines < 2) {
                area.selectRange(start + area.getTabSize() * lines, end + area.getTabSize() * lines);
            } else {
                area.selectRange(start, end + area.getTabSize() * lines);
            }
        }

        return true;
    }

    @Override
    public KeyCode getDefaultKeyCode() {
        return KeyCode.TAB;
    }
}
