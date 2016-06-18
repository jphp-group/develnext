package org.develnext.lexer.regex;

public class RegexKeywordLexerRule extends RegexLexerRule {
    public RegexKeywordLexerRule(String code, String... keywords) {
        super(code, "\\b(" + String.join("|", keywords) + ")\\b");
    }
}
