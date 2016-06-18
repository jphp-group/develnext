package org.develnext.lexer.regex;

import java.util.ArrayList;
import java.util.List;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class RegexLexer {
    protected List<RegexLexerRule> rules;
    protected String content;

    protected int lastKwEnd = 0;

    public RegexLexer() {
        rules = new ArrayList<>();
    }

    public void addRule(RegexLexerRule rule) {
        rules.add(rule);
    }

    public void removeRule(RegexLexerRule rule) {
        rules.remove(rule);
    }

    public String getContent() {
        return content;
    }

    public void setContent(String content) {
        this.content = content;
        this.lastKwEnd = 0;
    }

    protected Pattern buildPattern() {
        StringBuilder sb = new StringBuilder();

        boolean first = true;

        for (RegexLexerRule rule : rules) {
            if (first) {
                first = false;
            } else {
                sb.append("|");
            }

            sb.append("(?<").append(rule.getCode()).append(">").append(rule.getPattern()).append(")");
        }

        return Pattern.compile(sb.toString());
    }

    public void reset() {
        lastKwEnd = 0;
    }

    public RegexToken nextToken() {
        Pattern pattern = buildPattern();

        Matcher matcher = pattern.matcher(getContent());

        while (matcher.find(lastKwEnd)) {
            for (RegexLexerRule rule : rules) {
                if (matcher.group(rule.getCode()) != null) {
                    RegexToken token = new RegexToken(
                            rule.getCode(), matcher.start(), matcher.end() + rule.getOffset(), matcher.group(0)
                    );

                    lastKwEnd = matcher.end() + rule.getOffset();

                    return token;
                }
            }
        }

        return null;
    }

    public List<RegexToken> allTokens() {
        RegexToken token;

        List<RegexToken> tokens = new ArrayList<>();

        while ((token = nextToken()) != null) {
            tokens.add(token);
        }

        return tokens;
    }
}
