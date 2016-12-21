package org.develnext.jphp.gui.designer.editor.syntax.impl;

import org.antlr.v4.runtime.ANTLRInputStream;
import org.antlr.v4.runtime.BufferedTokenStream;
import org.antlr.v4.runtime.Token;
import org.develnext.jphp.gui.designer.editor.syntax.AbstractCodeArea;
import org.develnext.lexer.js.ECMAScriptLexer;
import org.develnext.lexer.js.ECMAScriptParser;
import org.fxmisc.richtext.model.StyleSpansBuilder;

import java.util.Collection;
import java.util.Collections;

public class JavaScriptCodeArea extends AbstractCodeArea {

    public JavaScriptCodeArea() {
        super();
        setStylesheet(AbstractCodeArea.class.getResource("JavaScriptCodeArea.css").toExternalForm());
    }

    private static Collection<String> getStyleOfToken(Token token) {
        switch (token.getType()) {
            case ECMAScriptParser.MultiLineComment:
            case ECMAScriptParser.SingleLineComment:
                return Collections.singletonList("comment");

            case ECMAScriptParser.If:
            case ECMAScriptParser.Else:
            case ECMAScriptParser.Function:
            case ECMAScriptParser.For:
            case ECMAScriptParser.Case:
            case ECMAScriptParser.While:
            case ECMAScriptParser.Break:
            case ECMAScriptParser.Catch:
            case ECMAScriptParser.Try:
            case ECMAScriptParser.This:
            case ECMAScriptParser.Class:
            case ECMAScriptParser.Extends:
            case ECMAScriptParser.Export:
            case ECMAScriptParser.Import:
            case ECMAScriptParser.Implements:
            case ECMAScriptParser.Do:
            case ECMAScriptParser.Var:
            case ECMAScriptParser.Let:
            case ECMAScriptParser.Const:
            case ECMAScriptParser.Default:
            case ECMAScriptParser.Delete:
            case ECMAScriptParser.New:
                return Collections.singletonList("keyword");

            case ECMAScriptParser.Identifier:
                return Collections.singletonList("identifier");

            case ECMAScriptParser.And:
            case ECMAScriptParser.Assign:
            case ECMAScriptParser.BitAnd:
            case ECMAScriptParser.BitAndAssign:
            case ECMAScriptParser.BitNot:
            case ECMAScriptParser.BitOr:
            case ECMAScriptParser.BitOrAssign:
            case ECMAScriptParser.BitXOr:
            case ECMAScriptParser.BitXorAssign:
            case ECMAScriptParser.Plus:
            case ECMAScriptParser.PlusAssign:
            case ECMAScriptParser.PlusPlus:
            case ECMAScriptParser.Minus:
            case ECMAScriptParser.MinusAssign:
            case ECMAScriptParser.MinusMinus:
            case ECMAScriptParser.Multiply:
            case ECMAScriptParser.MultiplyAssign:
            case ECMAScriptParser.Divide:
            case ECMAScriptParser.DivideAssign:
            case ECMAScriptParser.LeftShiftArithmetic:
            case ECMAScriptParser.LeftShiftArithmeticAssign:
            case ECMAScriptParser.RightShiftArithmetic:
            case ECMAScriptParser.RightShiftArithmeticAssign:
            case ECMAScriptParser.RightShiftLogical:
            case ECMAScriptParser.RightShiftLogicalAssign:
            case ECMAScriptParser.Not:
            case ECMAScriptParser.Modulus:
            case ECMAScriptParser.LessThan:
            case ECMAScriptParser.LessThanEquals:
            case ECMAScriptParser.GreaterThanEquals:
            case ECMAScriptParser.Equals:
            case ECMAScriptParser.MoreThan:
            case ECMAScriptParser.ModulusAssign:
                return Collections.singletonList("operator");

            case ECMAScriptParser.Colon:
            case ECMAScriptParser.SemiColon:
            case ECMAScriptParser.Comma:
            case ECMAScriptParser.CloseBrace:
            case ECMAScriptParser.CloseBracket:
            case ECMAScriptParser.CloseParen:
            case ECMAScriptParser.OpenBrace:
            case ECMAScriptParser.OpenBracket:
            case ECMAScriptParser.OpenParen:
            case ECMAScriptParser.Dot:
                return Collections.singletonList("control");

            case ECMAScriptParser.StringLiteral:
            case ECMAScriptParser.RegularExpressionLiteral:
                return Collections.singletonList("string");

            case ECMAScriptParser.DecimalLiteral:
            case ECMAScriptParser.HexIntegerLiteral:
            case ECMAScriptParser.OctalIntegerLiteral:
                return Collections.singletonList("number");

            default:
                return Collections.emptyList();
        }
    }

    @Override
    protected void computeHighlighting(StyleSpansBuilder<Collection<String>> spansBuilder, String text) {
        ECMAScriptLexer lexer = new ECMAScriptLexer(new ANTLRInputStream(text));
        //ECMAScriptParser parser = new ECMAScriptParser(new BufferedTokenStream(lexer));

        int lastEnd = 0;

        for (Token token : lexer.getAllTokens()) {
            int startIndex = token.getStartIndex();

            int spacer = startIndex - lastEnd;

            if (spacer > 0) {
                spansBuilder.add(Collections.emptyList(), spacer);
            }

            Collection<String> styleOfToken = getStyleOfToken(token);

            int gap = token.getStopIndex() - startIndex + 1;
            spansBuilder.add(styleOfToken, gap);

            lastEnd = token.getStopIndex() + 1;
        }

        //parser.program();
    }
}
