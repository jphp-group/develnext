package org.develnext.jphp.gui.designer.editor.syntax.impl;

import java.util.Collection;
import java.util.Collections;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.antlr.v4.runtime.*;
import org.develnext.jphp.gui.designer.editor.syntax.AbstractCodeArea;
import org.develnext.jphp.gui.designer.editor.syntax.CodeAreaGutterNote;
import org.develnext.jphp.gui.designer.editor.syntax.hotkey.*;
import org.develnext.lexer.css.CSSLexer;
import org.develnext.lexer.css.CSSParser;
import org.develnext.lexer.regex.RegexToken;
import org.develnext.lexer.regex.css.FxCssRegexLexer;
import org.fxmisc.richtext.*;
import org.fxmisc.richtext.model.StyleSpansBuilder;

public class CssCodeArea extends AbstractCodeArea {
    protected final FxCssRegexLexer cssRegexLexer = new FxCssRegexLexer();

    public CssCodeArea() {
        super();

        registerHotkey(new AddTabsHotkey());
        registerHotkey(new RemoveTabsHotkey());
        registerHotkey(new DuplicateSelectionHotkey());
        registerHotkey(new AutoSpaceEnterHotkey());
        registerHotkey(new AutoBracketsHotkey());
        registerHotkey(new BackspaceHotkey());

        setStylesheet(AbstractCodeArea.class.getResource("CssCodeArea.css").toExternalForm());
    }

    @Override
    protected void computeHighlighting(StyleSpansBuilder<Collection<String>> spansBuilder, String text) {
        cssRegexLexer.setContent(text);

        int lastKwEnd = 0;
        RegexToken token;

        while ((token = cssRegexLexer.nextToken()) != null) {
            spansBuilder.add(Collections.emptyList(), token.getStart() - lastKwEnd);
            spansBuilder.add(Collections.singleton(token.getCode().toLowerCase()), token.getEnd() - token.getStart());
            lastKwEnd = token.getEnd();
        }

        spansBuilder.add(Collections.emptyList(), text.length() - lastKwEnd);
    }
}