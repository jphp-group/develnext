package org.develnext.jphp.gui.designer.editor.syntax.impl;

import org.develnext.jphp.gui.designer.editor.syntax.AbstractCodeArea;
import org.develnext.jphp.gui.designer.editor.syntax.hotkey.AbstractHotkey;
import org.develnext.jphp.gui.designer.editor.syntax.hotkey.DuplicateSelectionHotkey;
import org.develnext.lexer.regex.RegexToken;
import org.develnext.lexer.regex.css.FxCssRegexLexer;
import org.fxmisc.richtext.model.StyleSpansBuilder;

import java.util.Collection;
import java.util.Collections;

public class TextCodeArea extends AbstractCodeArea {

    public TextCodeArea() {
        super();

        registerHotkey(new DuplicateSelectionHotkey());
    }

    @Override
    protected void computeHighlighting(StyleSpansBuilder<Collection<String>> spansBuilder, String text) {
    }
}