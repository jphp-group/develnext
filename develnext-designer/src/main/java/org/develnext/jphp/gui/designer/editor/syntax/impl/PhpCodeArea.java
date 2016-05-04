package org.develnext.jphp.gui.designer.editor.syntax.impl;

import org.antlr.v4.runtime.*;
import org.develnext.jphp.gui.designer.editor.syntax.AbstractCodeArea;
import org.develnext.jphp.gui.designer.editor.syntax.CodeAreaGutterNote;
import org.develnext.lexer.php.PHPLexer;
import org.develnext.lexer.php.PHPParser;
import org.fxmisc.richtext.StyleSpansBuilder;

import java.util.Collection;
import java.util.Collections;


public class PhpCodeArea extends AbstractCodeArea {
    private final BaseErrorListener errorListener = new BaseErrorListener() {
        @Override
        public void syntaxError(Recognizer<?, ?> recognizer, Object offendingSymbol, int line, int charPositionInLine, String msg, RecognitionException e) {
            getGutter().addNote(line, new CodeAreaGutterNote("error", msg));
        }
    };

    public PhpCodeArea() {
        super();
        setStylesheet(AbstractCodeArea.class.getResource("PhpCodeArea.css").toExternalForm());
    }

    private static Collection<String> getStyleOfToken(Token token) {
        switch (token.getType()) {
            case PHPParser.Comment:
                return Collections.singletonList("comment");
            case PHPParser.HtmlHex:
                return Collections.singletonList("color");
            case PHPParser.StringType:
                return Collections.singletonList("string");
            case PHPParser.Numeric:
                return Collections.singletonList("number");
            case PHPParser.IsIdentical:
                return Collections.singletonList("keyword");
            default:
                switch (token.getText()) {
                    case "{":
                    case "}":
                    case ";":
                        return Collections.singletonList("control");
                }

                return Collections.emptyList();
        }
    }

    @Override
    protected void computeHighlighting(StyleSpansBuilder<Collection<String>> spansBuilder, String text) {
        ANTLRInputStream inputStream = new ANTLRInputStream(text);
        PHPLexer lex = new PHPLexer(inputStream);
        //lex.addErrorListener(errorListener);

        int lastEnd = 0;
        for (Token token : lex.getAllTokens()) {
            int spacer = token.getStartIndex() - lastEnd;

            if (spacer > 0) {
                spansBuilder.add(Collections.emptyList(), spacer);
            }

            Collection<String> styleOfToken = getStyleOfToken(token);

            if (!styleOfToken.isEmpty()) {
                int gap = token.getStopIndex() - token.getStartIndex() + 1;
                spansBuilder.add(styleOfToken, gap);

                lastEnd = token.getStopIndex() + 1;
            }
        }

        lex.reset();
        PHPParser cssParser = new PHPParser(new CommonTokenStream(lex));
        cssParser.addErrorListener(errorListener);
        cssParser.htmlDocument();
    }
}