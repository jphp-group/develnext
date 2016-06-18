package org.develnext.lexer.regex;

public class RegexLexerRule {
    protected String code;
    protected String pattern;

    protected int offset;

    public RegexLexerRule(String code, String pattern, int offset) {
        this.code = code;
        this.pattern = pattern;
        this.offset = offset;
    }

    public RegexLexerRule(String code, String pattern) {
        this.code = code;
        this.pattern = pattern;
    }

    public String getCode() {
        return code;
    }

    public String getPattern() {
        return pattern;
    }

    public int getOffset() {
        return offset;
    }
}
