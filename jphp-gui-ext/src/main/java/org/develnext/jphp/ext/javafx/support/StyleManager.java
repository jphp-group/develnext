package org.develnext.jphp.ext.javafx.support;

import com.sun.javafx.css.Declaration;
import com.sun.javafx.css.ParsedValueImpl;
import com.sun.javafx.css.Rule;
import com.sun.javafx.css.Stylesheet;
import com.sun.javafx.css.parser.CSSParser;
import org.develnext.jphp.ext.javafx.classes.UXNode;

import java.util.LinkedHashMap;
import java.util.Map;

public class StyleManager {
    protected final CSSParser cssParser;
    protected final UXNode node;

    protected Map<String, Declaration> declarationMap;
    protected Rule rule;
    protected Stylesheet stylesheet;

    public StyleManager(UXNode node) {
        this.cssParser = new CSSParser();
        this.node = node;

        update();
    }

    protected void updateStyle() {
        StringBuilder sb = new StringBuilder();

        for (Map.Entry<String, Declaration> entry : declarationMap.entrySet()) {
            sb
                    .append("-fx-").append(entry.getKey())
                    .append(": ")
                    .append(entry.getValue().getParsedValue().getValue())
                    .append("; ");
        }

        node.getWrappedObject().setStyle(sb.toString());
        update();
    }

    public void update() {
        this.stylesheet = cssParser.parseInlineStyle(node.getWrappedObject());
        this.rule = stylesheet.getRules().isEmpty() ? null : stylesheet.getRules().get(0);

        this.declarationMap = new LinkedHashMap<>();

        if (this.rule != null) {
            for (Declaration declaration : rule.getDeclarations()) {
                String name = declaration.getProperty().toLowerCase();

                if (name.startsWith("-fx-")) {
                    name = name.substring(4);
                }

                declarationMap.put(name, declaration);
            }
        }
    }

    public boolean has(String name) {
        return declarationMap.containsKey(name.toLowerCase());
    }

    public String get(String name) {
        if (has(name)) {
            return declarationMap.get(name.toLowerCase()).getParsedValue().getValue().toString();
        }

        return null;
    }

    public void set(Map<String, String> values) {
        for (Map.Entry<String, String> entry : values.entrySet()) {
            _set(entry.getKey(), entry.getValue());
        }

        updateStyle();
    }

    public void set(String name, String value) {
        _set(name, value);
        updateStyle();
    }

    public Map<String, String> all() {
        LinkedHashMap<String, String> map = new LinkedHashMap<>();

        for (Map.Entry<String, Declaration> entry : declarationMap.entrySet()) {
            map.put(entry.getKey(), entry.getValue().getParsedValue().getValue().toString());
        }

        return map;
    }

    @SuppressWarnings("unchecked")
    protected void _set(String name, String value) {
        name = name.toLowerCase();
        Declaration declaration = declarationMap.get(name);

        if (declaration == null) {
            declaration = new Declaration(name, new ParsedValueImpl(value, null), false);
            declarationMap.put(name, declaration);
        }
    }
}
