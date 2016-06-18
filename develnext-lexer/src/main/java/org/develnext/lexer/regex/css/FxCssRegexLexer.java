package org.develnext.lexer.regex.css;

import org.develnext.lexer.regex.RegexKeywordLexerRule;
import org.develnext.lexer.regex.RegexLexer;
import org.develnext.lexer.regex.RegexLexerRule;

public class FxCssRegexLexer extends RegexLexer {
    public FxCssRegexLexer() {
        super();

        addRule(new RegexKeywordLexerRule("KEYWORD", "null", "important", "fill", "url"));

        addRule(new RegexLexerRule("PAREN", "\\(|\\)"));
        addRule(new RegexLexerRule("BRACE", "\\{|\\}"));
        addRule(new RegexLexerRule("BRACKET", "\\[|\\]"));
        addRule(new RegexLexerRule("CONTROL", "\\;|\\,|\\:"));
        addRule(new RegexLexerRule("STRING", "((\"([^\"\\\\]|\\\\.)*\")|(\'([^\'\\\\]|\\\\.)*\'))"));
        addRule(new RegexLexerRule("COMMENT", "//[^\n]*" + "|" + "/\\*(.|\\R)*?\\*/"));
        addRule(new RegexLexerRule("SELECTOR", "(\\.|\\#)[a-zA-Z\\_\\-]+[a-zA-Z0-9\\_\\- \\.\\#]{0,}[ ]+(\\{|\\:)", -1));

        addRule(new RegexLexerRule("NUMBER", "[0-9]+(\\.[0-9]+)?"));
        addRule(new RegexLexerRule("COLOR", "\\#[\\dA-Fa-f]{2,6}"));

        addRule(new RegexLexerRule("ATTRIBUTE", "[a-zA-Z\\_\\-]{1}[a-zA-Z0-9\\_\\-]{0,}[ ]{0,}\\:", -1));

    }
}
